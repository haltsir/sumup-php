<?php

namespace Sumup\Api\Http\Exception;

use Sumup\Api\Exception\ApiErrorContainer;

class RequestException extends \Exception implements RequestExceptionInterface
{
    /**
     * @var ApiErrorContainer $errors
     */
    public $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }


    public function getError()
    {
        return $this->errors->first();
    }
}
