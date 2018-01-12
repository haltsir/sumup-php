<?php


namespace Tests\Integration\Customer;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Model\Checkout\Card;
use Sumup\Api\Model\Customer\Customer;
use Sumup\Api\Model\Customer\PaymentInstrument;
use Sumup\Api\Service\Customer\CustomerService;
use Sumup\Api\Service\Customer\PaymentInstrumentService;
use Sumup\Api\Service\Merchant\BusinessService;
use Sumup\Api\SumupClient;

class PaymentInstrumentTest extends TestCase
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
     * @var PaymentInstrumentService
     */
    protected $paymentInstrumentService;

    /**
     * @var CustomerService
     */
    protected $customerService;

    /**
     * @var Customer
     */
    protected $customer;

    public function setUp()
    {
        $this->markTestSkipped("Skipped PaymentInstrument Test. Acquirer  doesn't work.");

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
            $this->paymentInstrumentService = $this->client->createService('payment_instrument');
            $this->customerService = $this->client->createService('customer.service');

            $this->customer =  $this->customerService->create([  "customer_id" => 'customer-id-'.uniqid()]);

        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testCreatePaymentInstrument()
    {
        $data = ['type' => "card",
                 'card' => [
                     'name' => 'Test Customer',
                     'number' => '4222222222222',
                     'expiry_year' => '2018',
                     'expiry_month' => '12',
                     'cvv' => '123'
                 ]
        ];

        $paymentInstrument =  $this->paymentInstrumentService->create($this->customer->customerId, $data);

        $this->assertInstanceOf(PaymentInstrument::class, $paymentInstrument);
        $this->assertTrue($paymentInstrument->active);
        $this->assertEquals('card', $paymentInstrument->type);
        $this->assertInstanceOf(Card::class, $paymentInstrument->card);
    }

    public function getPaymentInstrument()
    {
        $paymentInstrument = $this->paymentInstrumentService->get($this->customer->customerId);

        $this->assertInstanceOf(PaymentInstrument::class,$paymentInstrument);
        $this->assertTrue($paymentInstrument->active);
        $this->assertEquals('card',$paymentInstrument->type);
        $this->assertInstanceOf(Card::class,$paymentInstrument->card);
    }

    public function disablePaymentInstrument()
    {
        $data = ['type' => "card",
                 'card' => [
                     'name' => 'Test Customer',
                     'number' => '4222222222222',
                     'expiry_year' => '2018',
                     'expiry_month' => '12',
                     'cvv' => '123'
                 ]
        ];

        $paymentInstrument =  $this->paymentInstrumentService->create($this->customer->customerId, $data);

        $this->assertTrue(
            $this->paymentInstrumentService->disable($this->customer->customerId,$paymentInstrument->token)
        );
    }
}