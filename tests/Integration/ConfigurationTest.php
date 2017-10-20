<?php

namespace Integration;

use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    const REQUIRED_CONFIGURATION_KEYS = ['endpoint', 'version', 'token_storage_type', 'token_storage'];

    function testLoadConfiguration()
    {
        $configuration = (new \Sumup\Api\Configuration\Configuration())->load();
        $this->assertInstanceOf(\Sumup\Api\Model\Configuration::class, $configuration);
    }

    function testConfigurationKeys()
    {
        $configuration = (new \Sumup\Api\Configuration\Configuration())->load();
        $this->assertInstanceOf(\Sumup\Api\Model\Configuration::class, $configuration);

        foreach (self::REQUIRED_CONFIGURATION_KEYS as $key) {
            $methodName = 'get' . underscoreToCamelCase($key);
            $this->assertTrue(method_exists($configuration, $methodName), 'Method '. $methodName .' does not exist.');
        }
    }
}
