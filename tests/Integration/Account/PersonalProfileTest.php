<?php

namespace Tests\Integration\Account;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Model\Merchant\Profile;
use Sumup\Api\Service\Account\PersonalProfileService;
use Sumup\Api\SumupClient;

class PersonalProfileTest extends TestCase
{
    /**
     * @var PersonalProfileService
     */
    protected $personalProfileService;
    protected $client;

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
            $this->client = new SumupClient($configuration);
            $this->personalProfileService = $this->client->createService('profile');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }

    }

    public function testCreatePersonalProfile()
    {
        $personalProfile = [
            "first_name" => "test_first_name",
            "last_name" => "test_last_name",
            "date_of_birth" => "08-06-1991",
            "mobile_phone" => "+447700900518",
            "address" => [
                "address_line1" => "example test 1",
                "address_line2" => "example test 2",
                "city" => "london",
                "country" => "GB",
                "region_id" => 434,
                "region_name" => "test name",
                "post_code" => "EC1A 1BB",
                "landline" => "+442071387901",
                "first_name" => "test_first_name",
                "last_name" => "test_last_name",
                "company" => "test-co"
            ]
        ];

        $result = $this->personalProfileService->create($personalProfile);

        $this->assertInstanceOf(Profile::class, $result);
    }

    public function testGetPersonalProfile()
    {
        $profile = $this->personalProfileService->get();

        $this->assertInstanceOf(Profile::class, $profile);
    }
} 
