<?php

namespace Sumup\Api\Validator;

class RequiredArgumentsValidator
{
    public static function validate($inputArguments, $requiredArguments)
    {
        return 0 === sizeof(array_diff($requiredArguments, array_keys($inputArguments)));
    }
}
