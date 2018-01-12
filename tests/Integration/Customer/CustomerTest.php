<?php


namespace Tests\Integration\Customer;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Service\Customer\CustomerService;
use Sumup\Api\Service\Merchant\BusinessService;
use Sumup\Api\SumupClient;

class CustomerTest extends TestCase
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
     * @var CustomerService
     */
    protected $customerService;


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
            $this->customerService = $this->client->createService('customer');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }

    }

    public function testCreateCustomer()
    {
        $customer_id = 'sumup-php-customer-'. uniqid();

        $data = [
            "customer_id" => $customer_id,
            "personal_details" => [
                "name" => "Test Test",
                "phone" => "+447700900518",
                "address" => [
                    "line1" => "Test Address Line 1",
                    "line2" => "Test Address Line 2",
                    "country" => "GB",
                    "postal_code" => "EC2Y 9AL",
                    "city" => "Test city",
                    "state" => "state test"
                ]
            ]
        ];

        $customer = $this->customerService->create($data);

        $this->assertEquals($customer->customerId, $customer_id);
    }
}