<?php

namespace Tests\Integration\Transaction;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Model\Checkout\Card;
use Sumup\Api\Model\Transaction\Acquirer;
use Sumup\Api\Model\Transaction\Receipt;
use Sumup\Api\Model\Transaction\Transaction;
use Sumup\Api\Service\Transaction\ReceiptService;
use Sumup\Api\SumupClient;

class ReceiptTest extends TestCase
{
    /**
     * @var ReceiptService
     */
    public $receiptService;

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
            $this->receiptService = $client->createService('receipt');

        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testGetReceipt(){

        $transactionId = 'f5a5736a-e31d-4fd8-9c95-ac9bc29fdffb';
        $options = ['mid'=>'MDLA2XYL'];
        $receipt = $this->receiptService->get($transactionId,$options);

        $this->assertInstanceOf(Receipt::class,$receipt);
        $this->assertInstanceOf(Transaction::class,$receipt->transaction);
        $this->assertInstanceOf(Card::class,$receipt->transaction->card);
        $this->assertInstanceOf(Acquirer::class,$receipt->transaction->card);
    }
}