<?php

namespace Tests\Integration\Shelf;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Http\Exception\RequiredArgumentException;
use Sumup\Api\Model\Product\Product;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Service\Merchant\ProductService;
use Sumup\Api\Service\Merchant\ShelfService;
use Sumup\Api\SumupClient;

class ProductTest extends TestCase
{
    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var SumupClient
     */
    protected $client;

    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * @var ShelfService
     */
    protected $shelfService;

    /**
     * @var array
     */
    protected $toDelete = [];

    public function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/../../../');
        $dotenv->load();
        $this->configuration = new Configuration();
        $this->configuration->setUsername(getenv('SUMUP_TEST_USERNAME'));
        $this->configuration->setPassword(getenv('SUMUP_TEST_PASSWORD'));
        $this->configuration->setClientId(getenv('SUMUP_TEST_CLIENT_ID'));
        $this->configuration->setApiEndpoint(getenv('SUMUP_TEST_ENDPOINT'));

        try {
            $this->client = new SumupClient($this->configuration);
            $this->shelfService = $this->client->createService('shelf');
            $this->productService = $this->client->createService('product');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testCreateProduct()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $product = $this->productService->create($shelf->id, ['title' => 'test product']);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertGreaterThan(0, $product->id);
        $this->assertGreaterThan(0, $product->shelfId);
        $this->assertEquals('test product', $product->title);

        $this->toDelete[] = $shelf;
    }

    public function testShouldThrowRequiredArgumentExceptionIfRequiredParamIsMissingOnCreateProduct()
    {
        $this->expectException(RequiredArgumentException::class);
        $shelf = $this->shelfService->create(['name' => 'test shelf']);

        $this->productService->create($shelf->id, ['InvalidArgument']);

        $this->toDelete[] = $shelf;
    }

    public function testListProducts()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->toDelete[] = $shelf;

        $this->productService->create($shelf->id, ['title' => 'test product']);

        $products = $this->productService->all($shelf->id);
        $this->assertInstanceOf(Collection::class, $products);
        $this->assertEquals(1, $products->count());

        $arrayedProducts = $products->all();
        $product = array_shift($arrayedProducts);

        $this->assertInstanceOf(Product::class, $product);
    }

    public function testGetProduct()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->toDelete[] = $shelf;

        $product = $this->productService->create($shelf->id, ['title' => 'test product']);
        $fetchedProduct = $this->productService->get($shelf->id, $product->id);
        $this->assertInstanceOf(Product::class, $fetchedProduct);
        $this->assertGreaterThan(0, $fetchedProduct->id);
        $this->assertGreaterThan(0, $fetchedProduct->shelfId);
        $this->assertEquals('test product', $fetchedProduct->title);
    }

    public function testUpdateProduct()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->toDelete[] = $shelf;

        $product = $this->productService->create($shelf->id, ['title' => 'test product']);
        $this->productService->update($shelf->id, $product->id, ['title' => 'updated test product']);
        $fetchedProduct = $this->productService->get($shelf->id, $product->id);
        $this->assertEquals('updated test product', $fetchedProduct->title);
    }

    public function testDeleteProduct()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->toDelete[] = $shelf;

        $product = $this->productService->create($shelf->id, ['title' => 'test product']);
        $this->assertTrue($this->productService->delete($shelf->id, $product->id));

        $this->expectException(RequestException::class);
        $this->productService->get($shelf->id, $product->id);
    }

    public function tearDown()
    {
        foreach ($this->toDelete as $shelf) {
            $this->shelfService->delete($shelf->id);
        }
    }
}
