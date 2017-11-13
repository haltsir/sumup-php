<?php

namespace Sumup\Api\Security\OAuth2;

use GuzzleHttp\Client;
use Psr\Cache\CacheItemPoolInterface;
use Sumup\Api\Request\Request;

class OAuthClient
{
    /**
     * @var CacheItemPoolInterface
     */
    protected $cacheItemPool;

    /**
     * @var Client
     */
    protected $httpClient;

    public function __construct(
        CacheItemPoolInterface $cacheItemPool
    )
    {
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * @param array $scope
     * @param Request $request
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function request(array $scope, Request $request)
    {
        $options = [
            'headers' => $this->cacheItemPool->getItem('sumup_access_token')->get()
        ];

        if ('POST' === $request->getMethod()) {
            $body = $request->getBody();
            $request->setBody($body + ['scope' => implode(',', $scope)]);
        }

        return $request->send($options);
    }
}
