<?php

namespace Integration\Account;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Http\Exception\RequiredArgumentException;
use Sumup\Api\Model\Operator\Operator;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Service\Account\OperatorService;
use Sumup\Api\Service\Exception\InvalidArgumentException;
use Sumup\Api\SumupClient;
use Sumup\Api\Model\Employee\Employee;

class OperatorTest extends TestCase
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
     * @var OperatorService
     */
    protected $operatorService;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
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
            $this->operatorService = $this->client->createService('operator');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testGetListSubAccounts()
    {
        $results = $this->operatorService->all();
        $this->assertInstanceOf(Collection::class, $results);
    }

    public function testGetSubAccount()
    {
        $subAccount = $this->operatorService->create(['username' => $this->username, 'password' => $this->password]);
        $account = $this->operatorService->get($subAccount->id);

        $this->assertEquals($subAccount->id, $account->id);
        $this->assertEquals($this->username, $account->username);
    }

    public function testCreateSubAccount()
    {
        $subAcc = $this->operatorService->create(['username' => $this->username, 'password' => $this->password]);
        $this->assertInstanceOf(Operator::class, $subAcc);
        $this->assertEquals($this->username, $subAcc->username);
        $this->assertTrue(property_exists($subAcc, 'id'));
    }

    public function testShouldThrowInvalidArgumentExceptionWhenCreatingSubAccountIfInvalidArgumentIsPassed()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->operatorService->create(['InvalidArgument']);
    }

    public function testShouldThrowRequestExceptionIfUsernameExists()
    {
        $this->expectException(RequestException::class);

        $this->operatorService->create(['username' => $this->username, 'password' => $this->password]);
        $this->operatorService->create(['username' => $this->username, 'password' => $this->password]);
    }

    public function testUpdateSubAccount()
    {
        $updatedUsername = $this->generateRandomUsername();
        $subAcc = $this->operatorService->create(['username' => $this->username, 'password' => $this->password]);
        $success = $this->operatorService->update($subAcc->id, ['username' => $updatedUsername]);

        $this->assertTrue($success);

        $passwordUpdate = $this->operatorService->update($subAcc->id, ['password' => 'passWord123']);

        $this->assertTrue($passwordUpdate);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenUpdatingSubAccountIfIdIsEmptyString()
    {
        $this->expectException(RequiredArgumentException::class);
        $this->operatorService->update('', ['name' => $this->username]);
    }

    public function testDisableSubAccount()
    {
        $subAcc = $this->operatorService->create(['username' => $this->username, 'password' => $this->password]);
        $this->assertTrue($this->operatorService->disable($subAcc->id));
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
