<?php

namespace Integration\Merchant;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\SumupClient;

class MerchantProfileTest extends TestCase
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

    public function testCall()
    {
        $client = new SumupClient($this->configuration);
        $merchantProfileService = $client->createService('merchant.profile');

        $result = $merchantProfileService->get();
        $this->assertNotEmpty($result->merchantCode);
    }
}
