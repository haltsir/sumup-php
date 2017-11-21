<?php

namespace Sumup\Api\Security\Factory;

use GuzzleHttp\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Security\OAuth2\OAuthClient;

class OAuthClientFactory
{
    public function create(
        ConfigurationInterface $configuration,
        ClientInterface $httpClient,
        CacheItemPoolInterface $cache
    )
    {
        return new OAuthClient($configuration, $httpClient, $cache);
    }
}
