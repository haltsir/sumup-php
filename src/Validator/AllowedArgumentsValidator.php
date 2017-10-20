<?php

namespace Sumup\Api\Validator;

class AllowedArgumentsValidator
{
    public static function validate(array $inputArguments, array $allowedArguments)
    {
        return !sizeof(array_diff(array_keys($inputArguments), $allowedArguments));
    }
}
