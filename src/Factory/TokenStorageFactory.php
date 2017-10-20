<?php

namespace Sumup\Api\Factory;

use Sumup\Api\Model\Configuration;

class TokenStorageFactory
{
    /**
     * @var Configuration
     */
    protected $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function create()
    {
        $className = $this->configuration->getTokenStorage();
        if (!class_exists($className)) {
            throw new \Exception('Token storage ' . $className . ' does not exist.');
        }

        switch ($this->configuration->getTokenStorageType()) {
            case 'file':
                return new $className($this->configuration->getTokenStoragePath());
            default:
                return new $className();
        }
    }
}
