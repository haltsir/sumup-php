<?php

namespace Unit\Security\OAuth2;

use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Sumup\Api\Cache\File\FileCacheItem;
use Sumup\Api\Cache\File\FileCacheItemPool;
use Sumup\Api\Model\Client\Configuration;
use Sumup\Api\Request\Request;
use Sumup\Api\Security\Exception\OptionsException;
use Sumup\Api\Security\OAuth2\OAuthClient;

class OAuthClientTest extends TestCase {

    protected $cache;
    protected $config;
    protected $request;

    public function setUp()
    {
        $this->cache = new FileCacheItemPool();
        $this->config = new Configuration();
        $this->request = new Request();
        putenv('sumup_endpoint=https://api-theta.sam-app.ro');
    }

    public function testCreateOAuthClientWithMissingCacheParams()
    {
        $client = new OAuthClient($this->config);

        $this->expectException(OptionsException::class);

        $client->request($this->request);
    }

    public function testCreateOAuthClient()
    {
        $this->config->setUsername('zdravko.iliev@sumup.com');
        $this->config->setPassword('sumup-password');
        $this->config->setClientId('MEAVA2TL');
        $this->config->setCache($this->cache);

        $client = new OAuthClient($this->config);

        $this->assertInstanceOf(OAuthClient::class, $client);
    }

    public function testFetchAccessTokenFromCache()
    {
        $this->assertTrue(false);
    }

    /**
     * @group testing
     */
    public function testFetchAccessTokenFromRemote()
    {
        $this->cache->clear();
        $this->config->setUsername('zdravko.iliev@sumup.com');
        $this->config->setPassword('sumup-password');
        $this->config->setClientId('MEAVA2TL');
        $this->config->setCache($this->cache);

        $client = new OAuthClient($this->config);

        // verify that token doesn't exist in cache
        $token = $this->cache->getItem('sumup_oauth_access_token')->get();
        $this->assertNull($token);

        // fetch token
        $client->request($this->request);
        
    }

    public function testCachedTokenExpiration()
    {
        $this->assertTrue(false);
    }

    public function testRequestCannotFetchAccessToken()
    {
        $this->assertTrue(false);
    }

}
