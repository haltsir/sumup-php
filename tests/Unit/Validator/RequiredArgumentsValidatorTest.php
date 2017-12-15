<?php

namespace Tests\Unit\Validator;

use PHPUnit\Framework\TestCase;
use Sumup\Api\Validator\RequiredArgumentsValidator;

class RequiredArgumentsValidatorTest extends TestCase
{
    public function testValidArguments()
    {
        $testFrom = ['testKey' => 'testValue', 'anotherTestKey' => 'anotherTestValue'];
        $testAgainst = ['testKey', 'anotherTestKey'];
        $this->assertTrue(RequiredArgumentsValidator::validate($testFrom, $testAgainst));

        $testFrom += ['yetAnotherTestKey' => 'yetAnotherTestValue'];
        $this->assertTrue(RequiredArgumentsValidator::validate($testFrom, $testAgainst));
    }

    public function testInvalidArguments()
    {
        $testFrom = ['testKey' => 'testValue'];
        $testAgainst = ['testKey', 'anotherTestKey'];
        $this->assertFalse(RequiredArgumentsValidator::validate($testFrom, $testAgainst));

        $testFrom += ['anotherTestKey' => 'anotherTestValue'];
        $this->assertTrue(RequiredArgumentsValidator::validate($testFrom, $testAgainst));
    }
}
