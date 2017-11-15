<?php

namespace Sumup\Api\Security\OAuth2;

use GuzzleHttp\Client;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Request\Request;
use Sumup\Api\Cache\Exception\InvalidArgumentException;
use Sumup\Api\Security\Exception\AccessTokenException;
use Sumup\Api\Security\Exception\OptionsException;
use Sumup\Api\Model\Client\Configuration as ClientConfiguration;

class OAuthClient implements OAuthClientInterface {

    /**
     * @var ClientConfiguration object
     */
    protected $config;

    public function __construct(ClientConfiguration $config)
    {
        $this->config = $config;
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

//        if ('POST' === $request->getMethod()) {
//            $body = $request->getBody();
//            $request->setBody($body + ['scope' => implode(',', $scope)]);
//        }

        return $request->send($options);
    }

    private function fetchAccessToken()
    {
        $cachePool = $this->config->getCache();

        if (!($cachePool instanceof CacheItemPoolInterface)) {
            throw new OptionsException('Missing required parameter.');
        }

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
                'username' => $this->config->getUsername(),
                'password' => $this->config->getPassword(),
                'client_id' => $this->config->getClientId(),
                'grant_type' => 'password'
            ]
        ]);

        return json_decode($response->getBody());
    }

}
