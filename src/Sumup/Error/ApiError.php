<?php

namespace Sumup\Api\Errors;


class ApiError
{
    /**
     * @var string $errorCode
     */
    public $errorCode;
    /**
     * @var string $param
     */
    public $param;
    /**
     * @var string $message
     */
    public $message;

    /**
     * ApiError constructor.
     * @param $errorCode
     * @param $param
     * @param $message
     */
    public function __construct($errorCode, $param, $message)
    {
        $this->errorCode = $errorCode;
        $this->param = $param;
        $this->message = $message;
    }
}