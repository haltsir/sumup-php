<?php
namespace Unit\Security\OAuth2;

use GuzzleHttp\Client;
use Sumup\Api\Cache\File\FileCacheItemPool;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Security\Factory\OAuthClientFactory;
use Sumup\Api\Security\OAuth2\OAuthClient;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;


class OAuthClientFactoryTest extends TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|ConfigurationInterface
     */
    private $config;

    public function setUp()
    {
        $this->config = $this->getMockBuilder(ConfigurationInterface::class)
                             ->getMock();
    }

    public function testShouldCreateOauthClient()
    {
        $client = (new OAuthClientFactory())->create($this->config,new Client(),new FileCacheItemPool());

        $this->assertInstanceOf(OAuthClient::class, $client);
    }
}
