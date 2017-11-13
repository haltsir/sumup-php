<?php

namespace Integration\Account;

use PHPUnit\Framework\TestCase;
use Sumup\Api\Service\Account\AccountService;
use Sumup\Api\SumupClient;

class AccountTest extends TestCase
{
    public function setUp()
    {
        putenv('sumup_endpoint=https://api-theta.sam-app.ro');
    }

    public function testCall()
    {
        $client = new SumupClient(
            [
                'username' => 'strahil.minev@sumup.com',
                'password' => 'my test beta password',
                'client_id' => 'PXSpzdBDRZ75TA0DeoKBKE1WgoxT'
            ]
        );
        $accountService = new AccountService($client);
        $result = $accountService->get();
        var_dump($result, $client);
    }
}
