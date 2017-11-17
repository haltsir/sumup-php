<?php
namespace Unit\Security\OAuth2;

use Sumup\Api\Model\Client\Configuration;
use Sumup\Api\Security\OAuth2\OAuthClient;
use Sumup\Api\Security\OAuth2\OAuthClientFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;


class OAuthClientFactoryTest extends TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|Configuration;
     */
    private $config;

    public function setUp()
    {
        $this->config = $this->getMockBuilder(Configuration::class)
                             ->getMock();
    }

    public function testShouldCreateOauthClient()
    {
        $client = (new OAuthClientFactory())->create($this->config);

        $this->assertInstanceOf(OAuthClient::class, $client);
    }
}
