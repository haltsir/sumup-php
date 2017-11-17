<?php

namespace Sumup\Api\Security\OAuth2;

use GuzzleHttp\Client;
use Psr\Cache\CacheItemPoolInterface;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Request\Request;
use Sumup\Api\Cache\Exception\InvalidArgumentException;
use Sumup\Api\Security\Exception\AccessTokenException;
use Sumup\Api\Security\Exception\OptionsException;
use Sumup\Api\Model\Client\Configuration as ClientConfiguration;

class OAuthClient implements OAuthClientInterface
{
    /**
     * @var ClientConfiguration object
     */
    protected $config;

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var CacheItemPoolInterface
     */
    protected $cachePool;

    /**
     * @var Configuration
     */
    protected $appSettings;

    public function __construct(ClientConfiguration $config, Configuration $appSettings, $guzzleHttpClient,
                                CacheItemPoolInterface $cache)
    {
        $this->config = $config;
        $this->httpClient = $guzzleHttpClient;
        $this->cachePool = $cache;
        $this->appSettings = $appSettings;
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
        try {
            $cacheItem = $this->cachePool->getItem($this->config->getOauthTokenCacheKey());
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

            $this->cachePool->save($cacheItem);
            return $data->access_token;
        } catch (InvalidArgumentException $e) {
            throw new AccessTokenException($e->getMessage());
        }
    }

    private function fetchAccessTokenRemote()
    {
        $configuration = $this->appSettings->load();
        $response = $this->httpClient->post($configuration->getEndpoint() . '/token', [
            'json' => [
                'username' => $this->config->getUsername(),
                'password' => $this->config->getPassword(),
                'client_id' => $this->config->getClientId(),
                'grant_type' => 'password'
            ]
        ]);

        return json_decode($response->getBody());
    }

}
