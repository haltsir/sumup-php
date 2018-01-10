<?php

namespace Tests\Integration\Transaction;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Service\Transaction\TransactionService;
use Sumup\Api\SumupClient;

class TransactionTest extends TestCase
{
    /**
     * @var TransactionService
     */
    protected $transactionService;

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
            $this->transactionService = $client->createService('transaction');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testTransactionHistory()
    {
        $history = $this->transactionService->history();
        $this->assertObjectHasAttribute('items', $history);
        $this->assertObjectHasAttribute('links', $history);
        $this->assertInternalType('array', $history->items);
        $this->assertInternalType('array', $history->links);
    }

    public function testTransactionDetails()
    {
        $history = $this->transactionService->history();
        $transactionItem = array_shift($history->items);

        $transaction = $this->transactionService->details(
            ['transaction_code' => $transactionItem->transactionCode]
        );

        $this->assertEquals($transaction->transactionCode, $transactionItem->transactionCode);
    }

    public function testEmptyTransactionDetails()
    {
        $this->expectException(RequestException::class);
        $this->transactionService->details();
    }
}
