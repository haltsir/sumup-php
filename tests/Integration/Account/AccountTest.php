<?php

namespace Integration\Account;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Model\Merchant\Address;
use Sumup\Api\Service\Account\AccountService;
use Sumup\Api\SumupClient;

class AccountTest extends TestCase
{
    public function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/../../../');
        $dotenv->load();
        putenv('SUMUP_ENDPOINT=' . getenv('SUMUP_TEST_ENDPOINT'));
    }

    public function testCall()
    {
        $client = new SumupClient(
            [
                'username' => getenv('SUMUP_TEST_USERNAME'),
                'password' => getenv('SUMUP_TEST_PASSWORD'),
                'client_id' => getenv('SUMUP_TEST_CLIENT_ID')
            ]
        );
        $accountService = new AccountService($client);
        $result = $accountService->get();
        $this->assertInstanceOf(Address::class, $result->address);
    }
}
