<?php

namespace Integration\Account;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Model\Merchant\Address;
use Sumup\Api\SumupClient;

class AccountTest extends TestCase
{
    public function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/../../../');
        $dotenv->load();
    }

    public function testCall()
    {
        $configuration = new Configuration();
        $configuration->setUsername(getenv('SUMUP_TEST_USERNAME'));
        $configuration->setPassword(getenv('SUMUP_TEST_PASSWORD'));
        $configuration->setClientId(getenv('SUMUP_TEST_CLIENT_ID'));
        $configuration->setApiEndpoint(getenv('SUMUP_TEST_ENDPOINT'));

        $client = new SumupClient($configuration);
        $accountService = $client->createService('account');

        $result = $accountService->get();
        $this->assertInstanceOf(Address::class, $result->address);
    }
}
