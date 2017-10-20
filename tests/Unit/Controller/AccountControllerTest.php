<?php

namespace Unit\Controller;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Sumup\Api\Service\Account\AccountService;

class AccountControllerTest extends TestCase
{
    public function testStub()
    {
        $response = new Response();
        $service = $this->createMock(AccountService::class);
        $service->method('get')
                ->willReturn($this->returnValue($response));
        $this->assertInstanceOf(Response::class, $response);
    }
}
