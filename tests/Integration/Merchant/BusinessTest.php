<?php

namespace Tests\Integration\Merchant;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Service\Merchant\BusinessService;
use Sumup\Api\SumupClient;

class BusinessTest extends TestCase
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
     * @var BusinessService
     */
    protected $businessService;

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
            /** @var BusinessService businessService */
            $this->businessService = $this->client->createService('business');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testBusiness()
    {
        $business = $this->businessService->get();

        $data = [
            'business_name' => 'Test Business',
            'email' => 'test.business@sumup.com',
            'address' => [
                'address_line1' => 'Test Address Line 1',
                'city' => 'Issaquah',
                'country' => 'US',
                "post_code" => "90001",
                "landline" => "425-877-5910",
                'region_id' => 480
            ]
        ];

        $this->assertTrue($this->businessService->update($data));

        $updateData = [
            'business_name' => 'Updated Test Business',
            'address' => [
                'address_line1' => 'Updated Test Address Line 1',
                'city' => 'Seattle',
                'country' => 'US',
                'post_code' => '90001',
                'landline' => '425-877-5913',
                'region_id' => 480
            ]
        ];
        $this->businessService->update($updateData);

        $this->assertEquals($updateData['business_name'], $business->name);
        $this->assertEquals($updateData['address']['address_line1'], $business->address->addressLine1);
    }
}
