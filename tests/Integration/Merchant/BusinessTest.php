<?php

namespace Integration\Merchant;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
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

// TODO find a way to create business address with the same country as personal profile and set post code and landline for this country
//    public function testBusiness()
//    {
//        $business = $this->businessService->get();
//
//        $data = [
//            'business_name' => 'Test Business',
//            'email' => 'test.business@sumup.com',
//            'address' => [
//                'address_line1' => 'Test Address Line 1',
//                'city' => 'London',
//                'country' => 'US',
//                "post_code" => "90001",
//                "landline"=> "2345678910",
//                'region_id' => 16
//            ]
//        ];
//        $this->assertTrue($this->businessService->update($data));
//
//        $updateData = [
//            'business_name' => 'Updated Test Business',
//            'address' => [
//                'address_line1' => 'Updated Test Address Line 1',
//                'city' => 'Updated Test City',
//                'country' => 'GB',
//                'post_code' => 'EC2Y 9AK',
//                'landline' => '+442071387901',
//                'region_id' => 434
//            ]
//        ];
//        $this->businessService->update($updateData);
//
//        $this->assertEquals($updateData['business_name'], $business->name);
//        $this->assertEquals($updateData['address']['address_line1'], $business->address->addressLine1);
//    }
}
