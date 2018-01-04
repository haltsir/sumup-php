<?php

namespace Tests\Integration\Checkout;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Model\Checkout\Checkout;
use Sumup\Api\Service\Checkout\CheckoutService;
use Sumup\Api\SumupClient;

class CheckoutTest extends TestCase
{
    /**
     * @var CheckoutService
     */
    protected $checkoutService;

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
            $client = new SumupClient($configuration);
            $this->checkoutService = $client->createService('checkout');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testCheckout()
    {
        $data = [
            'checkout_reference' => 'sumup-php-checkout-' . uniqid(),
            'amount' => 10,
            'currency' => 'GBP',
            'pay_to_email' => getenv('SUMUP_TEST_USERNAME'),
            'pay_from_email' => 'test@example.org',
            'fee_amount' => 2,
            'description' => 'test checkout',
            'return_url' => 'none'
        ];

        /** @var Checkout $checkout */
        $checkout = $this->checkoutService->create($data);

        $this->assertInstanceOf(Checkout::class, $checkout);
        $this->assertNotEmpty($checkout->id);
        $this->assertEquals($data['checkout_reference'], $checkout->checkoutReference);
        $this->assertEquals($data['amount'], $checkout->amount);
        $this->assertEquals($data['currency'], $checkout->currency);
        $this->assertEquals($data['pay_to_email'], $checkout->payToEmail);
        $this->assertEquals($data['pay_from_email'], $checkout->payFromEmail);
        $this->assertEquals($data['description'], $checkout->description);
        $this->assertEquals($data['return_url'], $checkout->returnUrl);
        $this->assertEquals($checkout->status, 'PENDING');
        $this->assertNotEmpty($checkout->date);
        $this->assertSameSize([], $checkout->transactions);
    }

    /**
     * @group checkout
     */
    public function testCompleteCheckout()
    {
        $checkoutData = [
            'checkout_reference' => 'sumup-php-checkout-' . uniqid(),
            'amount' => 10,
            'currency' => 'GBP',
            'pay_to_email' => getenv('SUMUP_TEST_USERNAME'),
            'pay_from_email' => 'test@example.org',
            'fee_amount' => 2,
            'description' => 'test checkout',
            'return_url' => 'none'
        ];

        /** @var Checkout $checkout */
        $checkoutCreated = $this->checkoutService->create($checkoutData);

        $completeData = [
            'payment_type' => 'card',
            'card' => [
                'name' => 'Test Testov',
                'number' => 4222222222222,
                'expiry_year' => (string)date('Y'),
                'expiry_month' => '12',
                'cvv' => 123
            ]
        ];

        /** @var Checkout $checkoutCompleted */
        $checkoutCompleted = $this->checkoutService->complete($checkoutCreated->id, $completeData);

        $this->assertEquals($checkoutCreated->id, $checkoutCompleted->id);
        $this->assertEquals($checkoutCreated->checkoutReference, $checkoutCompleted->checkoutReference);
        $this->assertNotSameSize([], $checkoutCompleted->transactions);
    }
}
