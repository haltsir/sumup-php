<?php

namespace Tests\Integration\App;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Model\Mobile\Settings;
use Sumup\Api\SumupClient;

class AppSettingsTest extends TestCase
{
    public $client;

    public $appSettingsService;


    public function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/../../../');
        $dotenv->load();

        $configuration = new Configuration();
        $configuration->setUsername(getenv('SUMUP_TEST_USERNAME'));
        $configuration->setPassword(getenv('SUMUP_TEST_PASSWORD'));
        $configuration->setClientId(getenv('SUMUP_TEST_CLIENT_ID'));
        $configuration->setApiEndpoint(getenv('SUMUP_TEST_ENDPOINT'));


        try {
            $this->client = new SumupClient($configuration);
            $this->appSettingsService = $this->client->createService('app.settings');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }

    }

    public function testGetAppSettings()
    {
        $appSettings = $this->appSettingsService->all();
        $this->assertInstanceOf(Settings::class, $appSettings);
    }

    public function testUpdateAppSettings()
    {
        $data = [
            "terminal_mode_tutorial" => true,
            "mobile_payment_tutorial" => true,
            "manual_entry_tutorial" => true,
            "advanced_mode" => "OFF",
            "include_vat" => true,
            "checkout_preference" => "air",
            "mobile_payment" => "ON",
            "reader_payment" => "ON",
            "tipping" => "ON",
            "tip_rates" => [
                1
            ],
            "barcode_scanner" => "ON",
            "referral" => "ON"
        ];

        $this->appSettingsService->update($data);

        $appSettings = $this->appSettingsService->all();
        $this->assertEquals('air', $appSettings->checkoutPreference);
    }
}
