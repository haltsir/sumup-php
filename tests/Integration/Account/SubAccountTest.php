<?php

namespace Integration\Account;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Http\Exception\RequiredArgumentException;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Service\Account\SubaccountService;
use Sumup\Api\Service\Exception\InvalidArgumentException;
use Sumup\Api\SumupClient;
use Sumup\Api\Model\Employee\Employee;

class SubAccountTest extends TestCase
{
    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var SumupClient
     */
    protected $client;

    /**
     * @var SubaccountService
     */
    protected $subAccountService;

    /**
     * @var string
     */
    protected $username;

    protected $password;


    public function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/../../../');
        $dotenv->load();

        $this->config = new Configuration();
        $this->config->setUsername(getenv('SUMUP_TEST_USERNAME'));
        $this->config->setPassword(getenv('SUMUP_TEST_PASSWORD'));
        $this->config->setClientId(getenv('SUMUP_TEST_CLIENT_ID'));
        $this->config->setApiEndpoint(getenv('SUMUP_TEST_ENDPOINT'));
        $this->username = $this->generateRandomUsername();
        $this->password = 'Password123';

        try {
            $this->client = new SumupClient($this->config);
            $this->subAccountService = $this->client->createService('subaccount');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testGetListSubAccounts()
    {
        $results = $this->subAccountService->all();
        $this->assertInstanceOf(Collection::class, $results);
    }

    public function testGetSubAccount()
    {
        $subAccount = $this->subAccountService->create(['username' => $this->username, 'password' => $this->password]);
        $account = $this->subAccountService->get($subAccount->id);

        $this->assertEquals($subAccount->id, $account->id);
        $this->assertEquals($this->username, $account->username);
    }

    public function testCreateSubAccount()
    {
        $subAcc = $this->subAccountService->create(['username' => $this->username, 'password' => $this->password]);
        $this->assertInstanceOf(Employee::class, $subAcc);
        $this->assertTrue(property_exists($subAcc, 'id'));
    }

    public function testShouldThrowInvalidArgumentExceptionWhenCreatingSubAccountIfInvalidArgumentIsPassed()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->subAccountService->create(['InvalidArgument']);
    }

    public function testShouldThrowRequestExceptionIfUsernameExists()
    {
        $this->expectException(RequestException::class);

        $this->subAccountService->create(['username' => $this->username, 'password' => $this->password]);
            $this->subAccountService->create(['username' => $this->username, 'password' => $this->password]);
    }

    public function testUpdateSubAccount()
    {
        $updatedUsername = $this->generateRandomUsername();
        $subAcc = $this->subAccountService->create(['username' => $this->username, 'password' => $this->password]);
        $success = $this->subAccountService->update($subAcc->id, ['username' => $updatedUsername]);

        $this->assertTrue($success);

        $passwordUpdate = $this->subAccountService->update($subAcc->id, ['password' => 'passWord123']);

        $this->assertTrue($passwordUpdate);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenUpdatingSubAccountIfIdIsEmptyString()
    {
        $this->expectException(RequiredArgumentException::class);
        $this->subAccountService->update('', ['name' => $this->username]);
    }

    public function testDisableSubAccount()
    {
        $subAcc = $this->subAccountService->create(['username' => $this->username, 'password' => $this->password]);
        $this->assertTrue($this->subAccountService->disable($subAcc->id));
    }

    /**
     * Generate random username ( email )
     */
    function generateRandomUsername($length = 5, $mail = 'abv.bg')
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strtolower($randomString . '@' . $mail);
    }

}
