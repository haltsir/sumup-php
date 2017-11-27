<?php

namespace Integration\Shelf;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Service\Merchant\ShelfService;
use Sumup\Api\SumupClient;

class ShelfTest extends TestCase
{
    private $configuration;

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

        $result = $shelfService->all();
        $this->assertInstanceOf(Collection::class, $result);
    }
}
