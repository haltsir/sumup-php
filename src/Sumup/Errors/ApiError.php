<?php

namespace Sumup\Api\Errors;


class ApiError
{
    /**
     * @var string $error_code
     */
    public $error_code;
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
     * @param $error_code
     * @param $param
     * @param $message
     */
    public function __construct($error_code, $param, $message)
    {
        $this->error_code = $error_code;
        $this->param = $param;
        $this->message = $message;
    }
}