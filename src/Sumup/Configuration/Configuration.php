<?php

namespace Sumup\Api\Configuration;

class Configuration implements ConfigurationInterface
{
    /**
     * @var string
     */
    protected $apiEndpoint = null;

    /**
     * @var string
     */
    protected $apiVersion = 'v0.1';

    /**
     * @var int|string
     */
    protected $clientId = null;

    /**
     * @var string
     */
    protected $clientSecret = null;

    /**
     * @var string
     */
    protected $grantType = 'password';

    /**
     * @var string
     */
    protected $password = null;

    /**
     * @var string
     */
    protected $username = null;

    /**
     * @var string
     */
    protected $oAuthTokenCacheKey = 'sumup_oauth_access_token';

    /**
     * @return string
     */
    public function getApiEndpoint(): string
    {
        return $this->apiEndpoint;
    }

    /**
     * @param string $apiEndpoint
     */
    public function setApiEndpoint(string $apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;
    }

    /**
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * @param string $apiVersion
     */
    public function setApiVersion(string $apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * @return string
     */
    public function getFullEndpoint(): string
    {
        return rtrim($this->getApiEndpoint(), '/') . '/' . $this->getApiVersion();
    }

    /**
     * @return int|string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param int|string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret(string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getGrantType(): ?string
    {
        return $this->grantType;
    }

    /**
     * @param string $grantType
     */
    public function setGrantType(string $grantType)
    {
        $this->grantType = $grantType;
    }

    /**
     * @return string
     */
    public function getOAuthTokenCacheKey(): ?string
    {
        return $this->oAuthTokenCacheKey;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }
}
