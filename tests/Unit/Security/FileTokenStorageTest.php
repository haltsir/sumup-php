<?php

namespace Unit\Security;

use PHPUnit\Framework\TestCase;
use Sumup\Api\Security\Authentication\FileTokenStorage;

class FileTokenStorageTest extends TestCase
{
    /**
     * @covers FileTokenStorage::getToken()
     */
    public function testToken()
    {
        $tokenStorage = new FileTokenStorage(sys_get_temp_dir());
        $this->assertTrue($tokenStorage->setToken('testToken'));
        $this->assertEquals('testToken', $tokenStorage->getToken());
        $tokenStorage->resetStorage();
    }

    /**
     * @covers FileTokenStorage::getToken()
     */
    public function testGetNonexistantToken()
    {
        $tokenStorage = new FileTokenStorage(sys_get_temp_dir(), uniqid());
        $this->assertNull($tokenStorage->getToken());
    }

    /**
     * @covers FileTokenStorage::setToken()
     */
    public function testUnwritableDirectory()
    {
        $tokenStorage = new FileTokenStorage(DIRECTORY_SEPARATOR);
        $this->expectException(\Exception::class);
        $tokenStorage->setToken('testToken');
        $tokenStorage->resetStorage();
    }
}
