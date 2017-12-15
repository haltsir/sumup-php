<?php

namespace Tests\Integration\Payout;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Http\Exception\RequiredArgumentException;
use Sumup\Api\Model\Payout\BankAccount;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Service\Payout\BankAccountService;
use Sumup\Api\SumupClient;

class BankAccountTest extends TestCase
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
     * @var BankAccountService
     */
    protected $bankAccountService;

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
            $this->bankAccountService = $this->client->createService('bank_account');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testCreateBankAccount()
    {
        $bankAccount = $this->bankAccountService->all();

        if ($bankAccount instanceof Collection) {
            $this->markTestSkipped('Skip test. US User already have back Account');
        }

        $bankAccount = $this->bankAccountService->create(
            [
                'bank_code' => '091000022',
                'account_number' => 62136016,
                'account_holder_name' => 'Test Testov',
                'account_type' => 'SAVINGS'
            ]
        );
        $this->assertEquals('091000022', $bankAccount->bankCode);
        $this->assertEquals('62****16', $bankAccount->accountNumber);
        $this->assertEquals('Test Testov', $bankAccount->accountHolderName);
    }

    public function testShouldThrowRequestExceptionIfUsUserHaveMoreThanOneBankAccount()
    {
        $data = ['bank_code' => '091000022',
                 'account_number' => 62136016,
                 'account_holder_name' => 'Test Testov',
                 'account_type' => 'SAVINGS'];

        $this->expectException(RequestException::class);

        $this->bankAccountService->create($data);

    }


    public function testListBankAccounts()
    {
        $accounts = $this->bankAccountService->all();
        $this->assertInstanceOf(Collection::class, $accounts);
        $items = $accounts->all();
        /** @var BankAccount $account */
        $account = array_shift($items);
        $this->assertNotEmpty($account->bankCode);
    }

    public function testCreateBankAccountWithoutRequiredArgument()
    {
        $this->expectException(RequiredArgumentException::class);
        $this->bankAccountService->create([]);
    }

    public function testPrimaryBankAccount()
    {
        $bankAccount = $this->bankAccountService->primary();
        $this->assertTrue($bankAccount->primary);
    }
}
