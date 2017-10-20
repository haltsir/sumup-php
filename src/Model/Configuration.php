<?php

namespace Sumup\Api\Model;

class Configuration
{
    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $tokenStorageType;

    /**
     * @var string
     */
    private $tokenStorage;

    /**
     * @var string
     */
    private $tokenStoragePath;

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getFullEndpoint(): string
    {
        return $this->getEndpoint() . $this->getVersion();
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getTokenStorageType(): string
    {
        return $this->tokenStorageType;
    }

    /**
     * @param string $tokenStorageType
     */
    public function setTokenStorageType(string $tokenStorageType)
    {
        $this->tokenStorageType = $tokenStorageType;
    }

    /**
     * @return string
     */
    public function getTokenStorage(): string
    {
        return $this->tokenStorage;
    }

    /**
     * @param string $tokenStorage
     */
    public function setTokenStorage(string $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return string
     */
    public function getTokenStoragePath(): ?string
    {
        return $this->tokenStoragePath;
    }

    /**
     * @param string $tokenStoragePath
     */
    public function setTokenStoragePath(string $tokenStoragePath)
    {
        $this->tokenStoragePath = $tokenStoragePath;
    }
}
