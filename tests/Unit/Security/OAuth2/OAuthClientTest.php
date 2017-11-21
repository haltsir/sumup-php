<?php

namespace Unit\Security\OAuth2;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Sumup\Api\Cache\Exception\InvalidArgumentException;
use Sumup\Api\Cache\File\FileCacheItem;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Model\Client\Configuration as ClientConfig;
use Sumup\Api\Http\Request;
use Sumup\Api\Security\Exception\AccessTokenException;
use Sumup\Api\Security\OAuth2\OAuthClient;

class OAuthClientTest extends TestCase
{
    const OAUTH_CACHE_KEY = 'sumup_oauth_access_token';
    const OAUTH_CACHE_VAL = 'BBB';

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|CacheItemInterface
     */
    private $cacheItem;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|ClientConfig;
     */
    private $config;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|Request
     */
    private $request;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|Client
     */
    private $http;
    /**
     * @var OAuthClient
     */
    private $client;

    /**
     * @var Configuration
     */
    private $settings;


    public function setUp()
    {
        $this->config = $this->getMockBuilder(ClientConfig::class)
                             ->getMock();
        $this->settings = new Configuration();

        $this->http = $this->getMockBuilder(Client::class)
                           ->disableProxyingToOriginalMethods()
                           ->setMethods(['post'])
                           ->getMock();

        $this->cachePool = $this->getMockBuilder(CacheItemPoolInterface::class)
                                ->getMock();

        $this->client = new OAuthClient($this->config, $this->http, $this->cachePool);

        $this->request = $this->getMockBuilder(Request::class)
                              ->getMock();

        $this->cacheItem = $this->getMockBuilder(CacheItemInterface::class)
                                ->getMock();


        putenv('sumup_endpoint=https://api-theta.sam-app.ro');
    }

    public function testShouldFetchAccessTokenFromRemoteApiIfKeyIsNotPresentedInLocalCache()
    {
        $this->cachePool->expects($this->once())
                        ->method('getItem')
                        ->willReturn(new FileCacheItem(self::OAUTH_CACHE_KEY));

        $this->http->expects($this->once())
                   ->method('post')
                   ->willReturn(new Response(200, [], '{"access_token": "AAAA"}'));

        $this->request->expects($this->once())
                      ->method('send')
                      ->with([
                                 'headers' => [
                                     'Authorization' => 'Bearer AAAA'
                                 ]
                             ])
                      ->willReturn(true);

        $this->assertTrue($this->client->request($this->request));
    }

    public function testShouldFetchAccessTokenFromCacheIfKeyIsPresentedInLocalCache()
    {
        $this->cacheItem->expects($this->once())
                        ->method('get')
                        ->willReturn(self::OAUTH_CACHE_VAL);

        $this->cachePool->expects($this->once())
                        ->method('getItem')
                        ->willReturn($this->cacheItem);

        $this->http->expects($this->never())
                   ->method('post')
                   ->willReturn(false);

        $this->request->expects($this->once())
                      ->method('send')
                      ->with([
                                 'headers' => [
                                     'Authorization' => 'Bearer BBB'
                                 ]
                             ])
                      ->willReturn(true);

        $this->assertTrue($this->client->request($this->request));
    }


    public function testShouldSaveAccessTokenAfterRemoteApiCall()
    {
        $this->cacheItem->expects($this->once())
                        ->method('get')
                        ->willReturn(null);

        $this->cachePool->expects($this->once())
                        ->method('getItem')
                        ->willReturn($this->cacheItem);

        $this->http->expects($this->once())
                   ->method('post')
                   ->willReturn(new Response(200, [], '{"access_token": "AAAA"}'));

        $this->cacheItem->expects($this->once())
                        ->method('set')
                        ->with('AAAA');


        $this->cachePool->expects($this->once())
                        ->method('save')
                        ->with($this->cacheItem);

        $this->client->request($this->request);
    }


    public function testShouldSetExpirationDateIfKeyPresentedAfterApiCall()
    {
        $this->cacheItem->expects($this->once())
                        ->method('get')
                        ->willReturn(null);

        $this->cachePool->expects($this->once())
                        ->method('getItem')
                        ->willReturn($this->cacheItem);

        $this->http->expects($this->once())
                   ->method('post')
                   ->willReturn(new Response(200, [],
                                             '{"access_token": "AAAA","expires_in": 10}'));

        $this->cacheItem->expects($this->once())
                        ->method('set')
                        ->with('AAAA');

        $this->cacheItem->expects($this->once())
                        ->method('expiresAfter')
                        ->with(10);

        $this->cachePool->expects($this->once())
                        ->method('save')
                        ->with($this->cacheItem);

        $this->client->request($this->request);
    }

    public function testShouldThrowExceptionIfAccessTokenIsMissingAfterApiCall()
    {
        $this->cacheItem->expects($this->once())
                        ->method('get')
                        ->willReturn(null);

        $this->cachePool->expects($this->once())
                        ->method('getItem')
                        ->willReturn($this->cacheItem);

        $this->http->expects($this->once())
                   ->method('post')
                   ->willReturn(new Response(200, [],
                                             '{"missing-token": "missing-token","expires_in": 10}'));

        $this->expectException(AccessTokenException::class);

        $this->cacheItem->expects($this->never())->method('set');

        $this->client->request($this->request);
    }

    public function testShouldThrowExceptionIfCacheIsEmpty()
    {
        $this->expectException(AccessTokenException::class);

        $this->cachePool->expects($this->once())
                        ->method('getItem')
                        ->will($this->throwException(new InvalidArgumentException));

        $this->client->request($this->request);
    }

}
