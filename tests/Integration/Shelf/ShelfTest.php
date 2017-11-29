<?php

namespace Integration\Shelf;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Model\Product\Shelf;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Service\Merchant\ShelfService;
use Sumup\Api\SumupClient;

class ShelfTest extends TestCase
{
    /**
     * @var SumupClient
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $configuration;

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
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testListShelves()
    {
        $shelves = $this->shelfService->all();
        $this->assertInstanceOf(Collection::class, $shelves);
    }

    public function testGetShelf()
    {
        $shelves = $this->shelfService->all()
                                      ->all();
        $shelf = array_shift($shelves);
        $this->assertInstanceOf(Shelf::class, $shelf);

        $shelves = $this->shelfService->all(['products'])
                                      ->all();
        $shelf = array_shift($shelves);
        $this->assertObjectHasAttribute('products', $shelf);
    }

    public function testCreateShelf()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->assertInstanceOf(Shelf::class, $shelf);
        $this->assertAttributeGreaterThan(0, 'id', $shelf);

        $this->toDelete[] = $shelf;
    }

    public function testUpdateShelf()
    {
        $createdShelf = $this->shelfService->create(['name' => 'test shelf']);
        $success = $this->shelfService->update($createdShelf->id, ['name' => 'updated test shelf']);

        $this->assertTrue($success);

        $fetchedShelf = $this->shelfService->get($createdShelf->id);
        $this->assertEquals('updated test shelf', $fetchedShelf->name);

        $this->toDelete[] = $createdShelf;
    }

    public function testDeleteShelf()
    {
        $shelf = $this->shelfService->create(['name' => 'test shelf']);
        $this->assertTrue($this->shelfService->delete($shelf->id));

        $this->expectException(RequestException::class);
        $this->shelfService->get($shelf->id);
    }

    public function tearDown()
    {
        foreach ($this->toDelete as $shelf) {
            $this->shelfService->delete($shelf->id);
        }
    }
}
