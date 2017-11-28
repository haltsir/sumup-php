<?php

namespace Integration\Shelf;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Model\Product\Shelf;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Service\Merchant\ShelfService;
use Sumup\Api\SumupClient;

class ShelfTest extends TestCase
{
    private $configuration;
    private $toDelete = [];

    public function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/../../../');
        $dotenv->load();
        $this->configuration = new Configuration();
        $this->configuration->setUsername(getenv('SUMUP_TEST_USERNAME'));
        $this->configuration->setPassword(getenv('SUMUP_TEST_PASSWORD'));
        $this->configuration->setClientId(getenv('SUMUP_TEST_CLIENT_ID'));
        $this->configuration->setApiEndpoint(getenv('SUMUP_TEST_ENDPOINT'));
    }

    public function testListShelves()
    {
        $client = new SumupClient($this->configuration);
        /** @var ShelfService $shelfService */
        $shelfService = $client->createService('shelf');

        $shelves = $shelfService->all();
        $this->assertInstanceOf(Collection::class, $shelves);
    }

    public function testGetShelf()
    {
        $client = new SumupClient($this->configuration);
        /** @var ShelfService $shelfService */
        $shelfService = $client->createService('shelf');

        $shelves = $shelfService->all()
                                ->all();
        $shelf = array_shift($shelves);
        $this->assertInstanceOf(Shelf::class, $shelf);

        $shelves = $shelfService->all(['products'])
                                ->all();
        $shelf = array_shift($shelves);
        $this->assertObjectHasAttribute('products', $shelf);
    }

    public function testCreateShelf()
    {
        $client = new SumupClient($this->configuration);
        /** @var ShelfService $shelfService */
        $shelfService = $client->createService('shelf');

        $shelf = $shelfService->create(['name' => 'test shelf']);
        $this->assertInstanceOf(Shelf::class, $shelf);
        $this->assertAttributeGreaterThan(0, 'id', $shelf);

        $this->toDelete[] = $shelf;
    }

    public function testUpdateShelf()
    {
        $client = new SumupClient($this->configuration);
        /** @var ShelfService $shelfService */
        $shelfService = $client->createService('shelf');

        $createdShelf = $shelfService->create(['name' => 'test shelf']);
        $success = $shelfService->update($createdShelf->id, ['name' => 'updated test shelf']);

        $this->assertTrue($success);

        $fetchedShelf = $shelfService->get($createdShelf->id);
        $this->assertEquals('updated test shelf', $fetchedShelf->name);

        $this->toDelete[] = $createdShelf;
    }

    public function testDeleteShelf()
    {
        $client = new SumupClient($this->configuration);
        /** @var ShelfService $shelfService */
        $shelfService = $client->createService('shelf');
        $shelf = $shelfService->create(['name' => 'test shelf']);
        $this->assertTrue($shelfService->delete($shelf->id));
    }

    public function tearDown()
    {
        $client = new SumupClient($this->configuration);
        /** @var ShelfService $shelfService */
        $shelfService = $client->createService('shelf');
        foreach ($this->toDelete as $shelf) {
            $shelfService->delete($shelf->id);
        }
    }
}
