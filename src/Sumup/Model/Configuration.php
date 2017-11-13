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
    private $cacheItemPool;

    /**
     * @var string
     */
    private $fileCachePath;

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
    public function getCacheItemPool(): string
    {
        return $this->cacheItemPool;
    }

    /**
     * @param string $cacheItemPool
     */
    public function setCacheItemPool(string $cacheItemPool)
    {
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * @return string
     */
    public function getFileCachePath(): string
    {
        return $this->fileCachePath;
    }

    /**
     * @param string $fileCachePath
     */
    public function setFileCachePath(string $fileCachePath)
    {
        $this->fileCachePath = $fileCachePath;
    }
}
