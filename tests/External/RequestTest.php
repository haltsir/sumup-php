<?php

namespace External;

use PHPUnit\Framework\TestCase;
use Sumup\Api\Http\Request;

class RequestTest extends TestCase
{
    public function testInvalidUri()
    {
        $request = new Request();
        $this->expectException(\Exception::class);
        $request->setUri('example.org');
    }

    public function testSendRequest()
    {
        $request = new Request();
        $response = $request->setMethod('GET')
                            ->setUri('http://example.org')
                            ->send();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
    }

    public function testUnexpectedMethod()
    {
        $request = new Request();
        $this->expectException(\Exception::class);
        $request->setMethod('DUMMY');
    }

    public function testNoExplicitMethod()
    {
        $request = new Request();
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testSendEmptyUri()
    {
        $request = new Request;
        $this->expectException(\TypeError::class);
        $request->send();
    }
}
