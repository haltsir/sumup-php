<?php

namespace Tests\Unit\Validator;

use PHPUnit\Framework\TestCase;
use Sumup\Api\Validator\AllowedArgumentsValidator;

class AllowedArgumentsValidatorTest extends TestCase
{
    public function testValidArguments()
    {
        $testFrom = ['testKey'];
        $testAgainst = ['testKey', 'anotherTestKey'];

        $this->assertTrue(AllowedArgumentsValidator::validate($testFrom, $testAgainst));
    }

    public function testInvalidArguments()
    {
        $testFrom = ['testKey'];
        $testAgainst = ['anotherTestKey', 'dummyTestKey'];
        $this->assertFalse(AllowedArgumentsValidator::validate($testFrom, $testAgainst));

        $testFrom += ['anotherTestKey'];
        $this->assertFalse(AllowedArgumentsValidator::validate($testFrom, $testAgainst));

        array_shift($testFrom);
        $this->assertTrue(AllowedArgumentsValidator::validate($testFrom, $testAgainst));
    }
}
