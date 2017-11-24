<?php

namespace Integration\Account;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Model\Merchant\Profile;
use Sumup\Api\SumupClient;

class PersonalProfileTest extends TestCase
{
    public function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/../../../');
        $dotenv->load();
    }

    /**
     * @group test
     */
    public function testCall()
    {
        $configuration = new Configuration();
        $configuration->setUsername(getenv('SUMUP_TEST_USERNAME'));
        $configuration->setPassword(getenv('SUMUP_TEST_PASSWORD'));
        $configuration->setClientId(getenv('SUMUP_TEST_CLIENT_ID'));
        $configuration->setApiEndpoint(getenv('SUMUP_TEST_ENDPOINT'));


        $client = new SumupClient($configuration);
        $personalProfileService = $client->createService('personal_profile');

        $result = $personalProfileService->get();
        $this->assertInstanceOf(Profile::class, $result);
    }
} 