<?php

namespace Sumup\Api\Validator;

class AllowedArgumentsValidator
{
    public static function validate(array $inputArguments, array $allowedArguments)
    {
        return 0 === sizeof(array_diff(self::keysOrValues($inputArguments), $allowedArguments));
    }

    private static function keysOrValues($array)
    {
        return isAssociativeArray($array) ? array_keys($array) : array_values($array);
    }
}
