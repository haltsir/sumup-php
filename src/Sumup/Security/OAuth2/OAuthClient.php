<?php

namespace Sumup\Api\Security\OAuth2;

use GuzzleHttp\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Request;
use Sumup\Api\Cache\Exception\InvalidArgumentException;
use Sumup\Api\Security\Exception\AccessTokenException;

class OAuthClient implements OAuthClientInterface
{
    /**
     * @var ConfigurationInterface object
     */
    protected $configuration;

    /**
     * @var ClientInterface
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

    public function __construct(
        ConfigurationInterface $configuration,
        ClientInterface $httpClient,
        CacheItemPoolInterface $cache
    )
    {
        $this->configuration = $configuration;
        $this->httpClient = $httpClient;
        $this->cachePool = $cache;
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

    /**
     * Return access token. Generate new if needed.
     *
     * @return mixed
     * @throws AccessTokenException
     */
    private function fetchAccessToken()
    {
        try {
            $cacheItem = $this->cachePool->getItem($this->configuration->getOAuthTokenCacheKey());
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

    /**
     * Authenticate in the API.
     *
     * @return mixed
     */
    private function fetchAccessTokenRemote()
    {
        $response = $this->httpClient->post($this->configuration->getApiEndpoint() . '/token', [
            'json' => [
                'username' => $this->configuration->getUsername(),
                'password' => $this->configuration->getPassword(),
                'client_id' => $this->configuration->getClientId(),
                'grant_type' => 'password'
            ]
        ]);

        return json_decode($response->getBody());
    }
}
