<?php

namespace Sumup\Api\Service;

use Sumup\Api\Configuration\Configuration;
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
        $cacheItemPool = new ($this->configuration->getCacheItemPool())($this->configuration->getFileCachePath());
        $this->oAuthClient = new OAuthClient($cacheItemPool);
    }
}
