<?php

namespace Sumup\Api\Model\Client;


use Psr\Cache\CacheItemPoolInterface;
use Sumup\Api\Security\Exception\OptionsException;

class Configuration {

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $client_id;

    /**
     * @return string
     */
    private $oauth_token_cache_key = 'sumup_oauth_access_token';


    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {

        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     * @throws OptionsException
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param mixed $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @return string
     */
    public function getOauthTokenCacheKey()
    {
        return $this->oauth_token_cache_key;
    }
}