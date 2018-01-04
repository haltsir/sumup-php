<?php

namespace Tests\Integration\Merchant;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Model\Merchant\LegalType;
use Sumup\Api\Model\Merchant\Merchant;
use Sumup\Api\Service\Merchant\MerchantProfileService;
use Sumup\Api\SumupClient;

class MerchantProfileTest extends TestCase
{
    /**
     * @var MerchantProfileService
     */
    protected $merchantProfileService;

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
            $this->merchantProfileService = $client->createService('merchant.profile');
        } catch (SumupClientException $clientException) {
            $this->fail($clientException->getMessage());
        }
    }

    public function testUpdate()
    {
        $data = [
            'legal_type' => [
                'id' => 12
            ],
            'merchant_category_code' => 1520,
            'company_name' => 'Test Company',
            'address' => [
                'address_line1' => 'Test Address Line 1',
                'city' => 'Test City',
                'country' => 'GB',
                'post_code' => 'EC2Y 9AL',
                'landline' => '+442071387900'
            ]
        ];

        /** @var Merchant $merchant */
        $merchant = $this->merchantProfileService->update($data);

        $this->assertInstanceOf(Merchant::class, $merchant);
        $this->assertInstanceOf(LegalType::class, $merchant->legalType);
        $this->assertEquals('1520', $merchant->merchantCategoryCode);
    }

    public function testGetProfile()
    {
        /** @var Merchant $merchant */
        $merchant = $this->merchantProfileService->get();
        $this->assertNotEmpty($merchant->merchantCode);
    }
}
