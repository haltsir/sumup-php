<?php

namespace Unit\Request;

use PHPUnit\Framework\TestCase;
use Sumup\Api\Http\Request;

class RequestTest extends TestCase
{
    /**
     * @covers Request::getMethod()
     */
    public function testDefaultMethod()
    {
        $request = new Request();
        $this->assertEquals('GET', $request->getMethod());
    }

    /**
     * @covers Request::getMethod()
     * @covers Request::setMethod()
     */
    public function testMethod()
    {
        $request = new Request();
        $request->setMethod('POST');
        $this->assertEquals('POST', $request->getMethod());
    }

    /**
     * @covers Request::getUri()
     * @covers Request::setUri()
     */
    public function testUri()
    {
        $request = new Request();
        $request->setUri('http://example.org/test');
        $this->assertEquals('http://example.org/test', $request->getUri());
    }

    /**
     * @covers Request::getBody()
     * @covers Request::setBody()
     */
    public function testBody()
    {
        $request = new Request();
        $request->setBody(['property' => 'value', 'key' => 'dummy']);
        $this->assertArraySubset(['property' => 'value', 'key' => 'dummy'], $request->getBody());
    }
}
