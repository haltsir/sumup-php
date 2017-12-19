<?php

namespace External;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Http\Exception\Factory\RequestExceptionFactory;
use Sumup\Api\Http\Exception\MultipleRequestExceptions;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Http\Request;


class RequestTest extends TestCase
{
    protected $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | Client
     */
    protected $client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | ClientException
     */
    protected $exception;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | RequestExceptionFactory
     */
    protected $requestExceptionFactory;


    public function setUp()
    {
        $this->client = $this->getMockBuilder(ClientInterface::class)
                             ->getMock();

        $this->httpReq = $this->getMockBuilder(RequestInterface::class)->getMock();

        $this->httpRes = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $this->requestExceptionFactory = $this->getMockBuilder(RequestExceptionFactory::class)
                                              ->disableOriginalConstructor()->getMock();

        $this->request = new Request($this->client, $this->requestExceptionFactory);
    }

    public function testInvalidUri()
    {
        $this->expectException(\Exception::class);
        $this->request->setUri('example.org');
    }

    public function testSendRequest()
    {
        $this->client->expects($this->once())
                     ->method('request')
                     ->willReturn(new Response(200, [], '{}'));

        $response = $this->request->setMethod('GET')
                                  ->setUri('http://example.org')
                                  ->send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
    }

    public function testShouldFailToSendRequestAndThrowRequestExceptionWithInvalidParams()
    {
        $this->client
            ->expects($this->once())
            ->method('request')
            ->will($this->throwException(new ClientException('Error Message', $this->httpReq, $this->httpRes)));

        $this->requestExceptionFactory
            ->expects($this->once())
            ->method('createFromClientException')
            ->will($this->throwException(new RequestException([
                                                                  'error_code' => 'INVALID',
                                                                  'message' => 'Invalid Param',
                                                                  'param' => 'test'
                                                              ])));

        $this->expectException(RequestException::class);

        $this->request->setMethod('GET')
                      ->setUri('http://example.org')
                      ->send();
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
