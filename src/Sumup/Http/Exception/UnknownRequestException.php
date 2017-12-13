<?php

namespace Sumup\Api\Http\Exception;


class UnknownRequestException extends RequestException implements RequestExceptionInterface
{
    /**
     * @var string $error
     */
    public $error;

    public function __construct($errors)
    {
        $this->error = $errors;
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->error;
    }

}