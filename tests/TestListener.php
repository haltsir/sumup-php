<?php

namespace Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;

class TestListener extends TestSuite implements \PHPUnit\Framework\TestListener
{
    public function addWarning(Test $test, Warning $e, $time)
    {
        printf("Warning while running test '%s'.\n", $test->getName());
    }

    public function addError(Test $test, \Exception $e, $time)
    {
        printf("Error while running test '%s'.\n", $test->getName());
    }

    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
        printf("Test '%s' failed.\n", $test->getName());
    }

    public function addIncompleteTest(Test $test, \Exception $e, $time)
    {
        printf("Test '%s' is incomplete.\n", $test->getName());
    }

    public function addRiskyTest(Test $test, \Exception $e, $time)
    {
        printf("Test '%s' is deemed risky.\n", $test->getName());
    }

    public function addSkippedTest(Test $test, \Exception $e, $time)
    {
        printf("Test '%s' has been skipped.\n", $test->getName());
    }

    public function startTest(Test $test)
    {
        printf("Test '%s' started.\n", $test->getName());
    }

    public function endTest(Test $test, $time)
    {
        printf("Test '%s' ended.\n", $test->getName());
    }

    public function startTestSuite(TestSuite $suite)
    {
        // this should be Integration
        dd($suite->getName());

        if (strpos($suite->getName(), "Integration") !== false) {
            dd('xxxxxxxxxxxx');
        } else {
            dd('yyyyyyyyyyyy');
        }

        printf("TestSuite '%s' started.\n", $suite->getName());
    }

    public function endTestSuite(TestSuite $suite)
    {
        printf("TestSuite '%s' ended.\n", $suite->getName());
    }
}