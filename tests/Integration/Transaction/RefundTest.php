<?php

namespace Tests\Integration\Transaction;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Service\Account\PersonalProfileService;
use Sumup\Api\Service\Checkout\CheckoutService;
use Sumup\Api\Service\Transaction\RefundService;
use Sumup\Api\SumupClient;

class RefundTest extends TestCase
{
    /**
     * @var RefundService
     */
    public $refundService;

    /**
     * @var CheckoutService
     */
    public $checkoutService;

    /**
     * @var PersonalProfileService
     */
    public $personalProfileService;

    public function setUp()
    {
        $this->markTestSkipped("Skipped Refund Test. Acquirer  doesn't work.");

        $dotenv = new Dotenv(__DIR__ . '/../../../');
        $dotenv->load();
        $configuration = new Configuration();
        $configuration->setUsername(getenv('SUMUP_TEST_USERNAME'));
        $configuration->setPassword(getenv('SUMUP_TEST_PASSWORD'));
        $configuration->setClientId(getenv('SUMUP_TEST_CLIENT_ID'));
        $configuration->setApiEndpoint(getenv('SUMUP_TEST_ENDPOINT'));

        try {
            $client = new SumupClient($configuration);
            $this->refundService = $client->createService('refund');
            $this->checkoutService = $client->createService('checkout');
            $this->personalProfileService = $client->createService('personal_profile');

        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testRefund()
    {
        $checkoutData = [
            'checkout_reference' => 'sumup-php-checkout-' . uniqid(),
            'amount' => 10,
            'currency' => 'GBP',
            'pay_to_email' => getenv('SUMUP_TEST_USERNAME'),
            'pay_from_email' => 'test11@example.org',
            'fee_amount' => 2,
            'description' => 'test checkout',
            'return_url' => 'none'
        ];

        $completeData = [
            'payment_type' => 'card',
            'card' => [
                'name' => 'Test Testov',
                'number' => 4111111111111111,
                'expiry_year' => (string)date('Y'),
                'expiry_month' => '12',
                'cvv' => 123
            ]
        ];

        $body = [
            "amount" => 10
        ];

        $createdCheckout = $this->checkoutService->create($checkoutData);
        $checkoutResult = $this->checkoutService->complete($createdCheckout->id, $completeData);
        $this->assertTrue($this->refundService->refund($checkoutResult->transactions[0]->id, $body));
    }
}