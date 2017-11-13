<?php

namespace Sumup\Api\Service;

use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;

class SumupService
{
    /**
     * @var OAuthClientInterface
     */
    protected $client;

    /**
     * @var \Sumup\Api\Model\Configuration
     */
    protected $configuration;

    public function __construct(OAuthClientInterface $client)
    {
        $this->client = $client;
        $this->configuration = (new Configuration())->load();
    }
}
