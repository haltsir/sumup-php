<?php

namespace Unit\Validator;

use PHPUnit\Framework\TestCase;
use Sumup\Api\Validator\AllowedArgumentsValidator;

class AllowedArgumentsValidatorTest extends TestCase
{
    public function testValidArguments()
    {
        $testFrom = ['testKey' => 'value'];
        $testAgainst = ['testKey', 'anotherTestKey'];

        $this->assertTrue(AllowedArgumentsValidator::validate($testFrom, $testAgainst));
    }

    public function testInvalidArguments()
    {
        $testFrom = ['testKey' => 'value'];
        $testAgainst = ['anotherTestKey', 'dummyTestKey'];
        $this->assertFalse(AllowedArgumentsValidator::validate($testFrom, $testAgainst));

        $testFrom += ['anotherTestKey' => 'value'];
        $this->assertFalse(AllowedArgumentsValidator::validate($testFrom, $testAgainst));

        unset($testFrom['testKey']);
        $this->assertTrue(AllowedArgumentsValidator::validate($testFrom, $testAgainst));
    }
}
