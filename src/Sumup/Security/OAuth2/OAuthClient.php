<?php

namespace Sumup\Api\Security\OAuth2;

use GuzzleHttp\Client;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Request\Request;
use Sumup\Api\Cache\Exception\InvalidArgumentException;
use Sumup\Api\Cache\File\FileCacheItemPool;
use Sumup\Api\Security\Exception\AccessTokenException;
use Sumup\Api\Security\Exception\OptionsException;

class OAuthClient implements OAuthClientInterface
{
    const REQUIRED_OPTIONS = ['username', 'password', 'client_id'];

    /**
     * @var array
     */
    protected $options;

    public function __construct(array $options)
    {
        if (sizeof(array_diff(self::REQUIRED_OPTIONS, array_keys($options))) > 0) {
            throw new OptionsException('Missing required oAuth client options');
        }

        $this->options = array_merge($options, [
            'cache' => new FileCacheItemPool()
        ]);
    }

    /**
     * @param Request $request
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function request(Request $request)
    {
        $accessToken = $this->fetchAccessToken();

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ];

        return $request->send($options);
    }

    private function fetchAccessToken()
    {
        /** @var CacheItemPoolInterface $cachePool */
        $cachePool = $this->options['cache'];

        try {
            /** @var CacheItemInterface $cacheItem */
            $cacheItem = $cachePool->getItem('sumup_oauth_access_token');
            $cacheValue = $cacheItem->get();

            if (!empty($cacheValue)) {
                return $cacheValue;
            }

            $data = $this->fetchAccessTokenRemote();
            if (!isset($data->access_token)) {
                throw new AccessTokenException('Cannot fetch access token');
            }

            $cacheItem->set($data->access_token);

            if (isset($data->expires_in) && $data->expires_in > 0) {
                $cacheItem->expiresAfter($data->expires_in);
            }

            $cachePool->save($cacheItem);

            return $data->access_token;
        } catch (InvalidArgumentException $e) {
            throw new AccessTokenException($e->getMessage());
        }
    }

    private function fetchAccessTokenRemote()
    {
        $configuration = (new Configuration())->load();
        $httpClient = new Client();
        $response = $httpClient->post($configuration->getEndpoint() . '/token', [
            'json' => [
                'username' => $this->options['username'],
                'password' => $this->options['password'],
                'client_id' => $this->options['client_id'],
                'grant_type' => 'password'
            ]
        ]);

        return json_decode($response->getBody());
    }
}
