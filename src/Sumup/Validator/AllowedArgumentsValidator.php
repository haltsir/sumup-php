<?php

namespace Sumup\Api\Validator;

class AllowedArgumentsValidator
{
    public static function validate(array $inputArguments, array $allowedArguments)
    {
        return 0 === sizeof(array_diff(array_values($inputArguments), $allowedArguments));
    }
}
