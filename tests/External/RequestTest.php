<?php

namespace External;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Http\Request;

class RequestTest extends TestCase
{
    protected $request;

    public function setUp()
    {
        $this->request = new Request(new Client());
    }

    public function testInvalidUri()
    {
        $this->expectException(\Exception::class);
        $this->request->setUri('example.org');
    }

    public function testSendRequest()
    {
        $response = $this->request->setMethod('GET')
                                  ->setUri('http://example.org')
                                  ->send();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
    }

    public function testUnexpectedMethod()
    {
        $this->expectException(\Exception::class);
        $this->request->setMethod('DUMMY');
    }

    public function testNoExplicitMethod()
    {
        $this->assertEquals('GET', $this->request->getMethod());
    }

    public function testSendEmptyUri()
    {
        $this->expectException(\TypeError::class);
        $this->request->send();
    }
}
