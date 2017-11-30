<?php

namespace Integration\Shelf;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Model\Product\Price;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Service\Merchant\PriceService;
use Sumup\Api\Service\Merchant\ProductService;
use Sumup\Api\Service\Merchant\ShelfService;
use Sumup\Api\SumupClient;

class PriceTest extends TestCase
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
     * @var PriceService
     */
    protected $priceService;

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
            $this->priceService = $this->client->createService('price');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testCreatePrice()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->toDelete[] = $shelf;

        $product = $this->productService->create($shelf->id, ['title' => 'test product']);
        $price = $this->priceService->create($shelf->id, $product->id, ['net' => 1, 'description' => 'test price']);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertGreaterThan(0, $price->id);
        $this->assertGreaterThan(0, $price->productId);
        $this->assertEquals('test price', $price->description);
    }

    public function testListPrices()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->toDelete[] = $shelf;

        $product = $this->productService->create($shelf->id, ['title' => 'test product']);
        $this->priceService->create($shelf->id, $product->id, ['net' => 1]);

        $prices = $this->priceService->all($shelf->id, $product->id);
        $this->assertInstanceOf(Collection::class, $prices);
        $this->assertEquals(1, $prices->count());

        $arrayedPrices = $prices->all();
        $price = array_shift($arrayedPrices);

        $this->assertInstanceOf(Price::class, $price);
    }

    public function testGetPrice()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->toDelete[] = $shelf;

        $product = $this->productService->create($shelf->id, ['title' => 'test product']);
        $price = $this->priceService->create($shelf->id, $product->id, ['net' => 1, 'description' => 'test price']);
        $fetchedPrice = $this->priceService->get($shelf->id, $product->id, $price->id);
        $this->assertInstanceOf(Price::class, $fetchedPrice);
        $this->assertGreaterThan(0, $fetchedPrice->id);
        $this->assertGreaterThan(0, $fetchedPrice->productId);
        $this->assertEquals('test price', $fetchedPrice->description);
    }

    public function testUpdatePrice()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->toDelete[] = $shelf;

        $product = $this->productService->create($shelf->id, ['title' => 'test product']);
        $price = $this->priceService->create($shelf->id, $product->id, ['net' => 1, 'description' => 'test price']);
        $this->assertTrue($this->priceService->update($shelf->id, $product->id, $price->id,
                                                      ['net' => 2, 'description' => 'updated test price']));

        $fetchedPrice = $this->priceService->get($shelf->id, $product->id, $price->id);
        $this->assertEquals(2, $fetchedPrice->net);
        $this->assertEquals('updated test price', $fetchedPrice->description);
    }

    public function testDeleteProduct()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->toDelete[] = $shelf;

        $product = $this->productService->create($shelf->id, ['title' => 'test product']);
        $price = $this->priceService->create($shelf->id, $product->id, ['net' => 1]);
        $this->assertTrue($this->priceService->delete($shelf->id, $product->id, $price->id));

        $this->expectException(RequestException::class);
        $this->priceService->get($shelf->id, $product->id, $price->id);
    }

    public function tearDown()
    {
        foreach ($this->toDelete as $shelf) {
            $this->shelfService->delete($shelf->id);
        }
    }
}
