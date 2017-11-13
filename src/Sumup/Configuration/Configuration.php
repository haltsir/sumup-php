<?php

namespace Sumup\Api\Configuration;

use Sumup\Api\Model\Configuration as ConfigurationModel;

class Configuration
{
    /**
     * @var ConfigurationModel
     */
    protected $configuration;

    public function __construct()
    {
        $this->configuration = new ConfigurationModel();
    }

    /**
     * @return ConfigurationModel
     * @throws \Exception
     */
    public function load()
    {
        $configFileLocation = __DIR__ . '/../../../config/api.php';
        if (!file_exists($configFileLocation)) {
            throw new \Exception('Configuration file not found.');
        }

        $data = require $configFileLocation;
        if (!is_array($data)) {
            throw new \Exception('Unexpected configuration format.');
        }

        foreach ($data as $key => $val) {
            $methodName = 'set' . ucfirst(underscoreToCamelCase($key));
            if (false === method_exists($this->configuration, $methodName)) {
                throw new \Exception('Configuration property ' . $key . ' unknown.');
            }

            $this->configuration->{$methodName}($val);
        }

        return $this->configuration;
    }
}
