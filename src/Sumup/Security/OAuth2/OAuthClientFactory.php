<?php
/**
 * Created by PhpStorm.
 * User: zdrav
 * Date: 16-Nov-17
 * Time: 1:24 PM
 */

namespace Sumup\Api\Security\OAuth2;


use Sumup\Api\Cache\File\FileCacheItemPool;
use GuzzleHttp\Client;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Model\Client\Configuration as ClientConfig;

class OAuthClientFactory {

    public function create(ClientConfig $clientConfiguration)
    {
        return new OAuthClient($clientConfiguration, new Configuration(), new Client(), new FileCacheItemPool());
    }

}