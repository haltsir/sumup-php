<?php

namespace Sumup\Api\Validator;

class RequiredArgumentsValidator
{
    public static function validate($inputArguments, $requiredArguments)
    {
        return !sizeof(array_diff($requiredArguments, array_keys($inputArguments)));
    }
}
