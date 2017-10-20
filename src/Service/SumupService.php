<?php

namespace Sumup\Api\Service;

use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Factory\TokenStorageFactory;
use Sumup\Api\Security\OAuth2\OAuthClient;

class SumupService
{
    /**
     * @var \Sumup\Api\Model\Configuration
     */
    protected $configuration;

    /**
     * @var OAuthClient
     */
    protected $oAuthClient;

    public function __construct()
    {
        $this->configuration = (new Configuration())->load();
        $tokenStorageFactory = new TokenStorageFactory($this->configuration);
        $this->oAuthClient = new OAuthClient($tokenStorageFactory->create());
    }
}
