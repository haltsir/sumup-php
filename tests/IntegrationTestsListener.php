<?php

namespace Tests;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;

class IntegrationTestsListener extends TestSuite implements TestListener
{
    public $client;

    public function __construct()
    {
        parent::__construct();

        $dotenv = new Dotenv(__DIR__ . '/../');
        $dotenv->load();

        $this->client = new Client();
    }

    public function addWarning(Test $test, Warning $e, $time)
    {
        printf("Warning while running test '%s'.\n", $test->getName());
    }

    public function addError(Test $test, \Exception $e, $time)
    {
        printf("Error while running test '%s'.\n", $test->getName());
    }

    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
        printf("Test '%s' failed.\n", $test->getName());
    }

    public function addIncompleteTest(Test $test, \Exception $e, $time)
    {
        printf("Test '%s' is incomplete.\n", $test->getName());
    }

    public function addRiskyTest(Test $test, \Exception $e, $time)
    {
        printf("Test '%s' is deemed risky.\n", $test->getName());
    }

    public function addSkippedTest(Test $test, \Exception $e, $time)
    {
        printf("Test '%s' has been skipped.\n", $test->getName());
    }

    public function startTest(Test $test)
    {
        printf("Test '%s' started.\n", $test->getName());
    }

    public function endTest(Test $test, $time)
    {
        printf("Test '%s' ended.\n", $test->getName());
    }

    public function startTestSuite(TestSuite $suite)
    {
        if (strpos($suite->getName(), "Integration") !== false) {
            if (!getenv('SUMUP_TEST_USER_ID')) {
                putenv('SUMUP_TEST_USER_ID=' . uniqid());
                $authUser = $this->authenticate();
                $this->createTestUser($authUser->access_token);
            }
        }
    }

    public function endTestSuite(TestSuite $suite)
    {
        printf("TestSuite '%s' ended.\n", $suite->getName());
    }

    public function authenticate()
    {
        $response = $this->client->request('POST', getenv('SUMUP_TEST_ENDPOINT') . '/token', [
            'json' => [
                "grant_type" => "password",
                "client_id" => getenv('SUMUP_TEST_CLIENT_ID'),
                "username" => getenv('SUMUP_TEST_USERNAME'),
                "password" => getenv('SUMUP_TEST_PASSWORD')
            ]
        ]);

        return json_decode((string)$response->getBody());
    }

    public function createTestUser($accessToken)
    {

        $response = $this->client->request('POST', getenv('SUMUP_TEST_ENDPOINT') . '/v0.1/users',
                                           ['json' => $this->createUserWithSumupPrefix(),
                                            'headers' => ['Authorization' => 'Bearer ' . $accessToken]]);

        $this->setUserEnv(json_decode((string)$response->getBody()));

    }

    public function createUserWithSumupPrefix()
    {
        return [
            'country' => 'GB',
            'credentials' => [
                'username' => getenv('SUMUP_TEST_ACCOUNT_PREFIX') . getenv('SUMUP_TEST_USER_ID') . '@example.org',
                'password' => getenv('SUMUP_TEST_PASSWORD')
            ]
        ];
    }

    public function setUserEnv(\stdClass $response)
    {
        putenv('SUMUP_TEST_USERNAME=' . $response->account->username);
    }
}