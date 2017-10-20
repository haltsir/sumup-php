<?php

namespace Unit\Security;

use PHPUnit\Framework\TestCase;
use Sumup\Api\Factory\TokenStorageFactory;
use Sumup\Api\Model\Configuration;
use Sumup\Api\Security\Authentication\FileTokenStorage;

class TokenStorageFactoryTest extends TestCase
{
    /**
     * @covers TokenStorageFactory::create()
     */
    public function testGenerateFileStorage()
    {
        $configuration = new Configuration();
        $configuration->setTokenStorage(FileTokenStorage::class);
        $configuration->setTokenStoragePath(sys_get_temp_dir());
        $configuration->setTokenStorageType('file');

        $tokenStorageFactory = new TokenStorageFactory($configuration);

        $this->assertInstanceOf(FileTokenStorage::class,
                                $tokenStorageFactory->create());
    }

    /**
     * @covers TokenStorageFactory::create()
     */
    public function testGenerateDummyStorage()
    {
        $configuration = new Configuration();
        $configuration->setTokenStorage(FileTokenStorage::class);
        $configuration->setTokenStoragePath(sys_get_temp_dir());
        $configuration->setTokenStorageType('dummy');

        $tokenStorageFactory = new TokenStorageFactory($configuration);

        $this->expectException(\ArgumentCountError::class);

        $this->assertNotInstanceOf(FileTokenStorage::class,
                                   $tokenStorageFactory->create());
    }
}
