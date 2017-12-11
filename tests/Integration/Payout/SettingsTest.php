<?php

namespace Integration\Payout;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Model\Payout\Settings;
use Sumup\Api\Service\Payout\SettingsService;
use Sumup\Api\SumupClient;

class SettingsTest extends TestCase
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
     * @var SettingsService
     */
    protected $settingsService;

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
            $this->settingsService = $this->client->createService('payout.settings');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testGetSettings()
    {
        $settings = $this->settingsService->get();
        $this->assertInstanceOf(Settings::class, $settings);
    }

    public function testUpdateSettings()
    {
        $this->assertTrue($this->settingsService->update(['payout_period' => 'daily']));
        /** @var Settings $settings */
        $settings = $this->settingsService->get();
        $this->assertEquals('daily', $settings->payoutPeriod);
        $this->assertTrue($this->settingsService->update(['payout_period' => 'weekly']));
        $settings = $this->settingsService->get();
        $this->assertEquals('weekly', $settings->payoutPeriod);
    }
}
