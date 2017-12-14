<?php

namespace Unit\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Http\Exception\Factory\RequestExceptionFactory;
use Sumup\Api\Http\Request;

class RequestTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | Client
     */
    protected $client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | RequestExceptionFactory
     */
    protected $requestExceptionFactory;

    protected $request;

    public function setUp()
    {
        $this->client = $this->getMockBuilder(ClientInterface::class)
                             ->getMock();

        $this->requestExceptionFactory = $this->getMockBuilder(RequestExceptionFactory::class)
                                              ->disableOriginalConstructor()->getMock();


        $this->request = new Request($this->client, $this->requestExceptionFactory);
    }

    public function testDefaultMethod()
    {
        $this->assertEquals('GET', $this->request->getMethod());
    }

    public function testMethod()
    {
        $this->request->setMethod('POST');
        $this->assertEquals('POST', $this->request->getMethod());
    }

    public function testUri()
    {
        $this->request->setUri('http://example.org/test');
        $this->assertEquals('http://example.org/test', $this->request->getUri());
    }

    public function testBody()
    {
        $this->request->setBody(['property' => 'value', 'key' => 'dummy']);
        $this->assertArraySubset(['property' => 'value', 'key' => 'dummy'], $this->request->getBody());
    }

    public function testSetJson()
    {
        $this->request->setJson('{"prop": "val"}');
        $this->assertArraySubset(['prop' => 'val'], $this->request->getBody());
    }
}
