<?php

namespace Sumup\Api\Http\Exception;


class MultipleRequestExceptions extends RequestException implements RequestExceptionInterface
{
    /**
     * @var array $errors
     */
    public $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->errors;
    }
}