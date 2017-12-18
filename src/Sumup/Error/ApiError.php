<?php

namespace Sumup\Api\Error;


class ApiError
{
    /**
     * @var string $errorCode
     */
    private $errorCode;
    /**
     * @var string $param
     */
    private $param;
    /**
     * @var string $message
     */
    private $message;

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     * @return ApiError
     */
    public function setErrorCode(string $errorCode = null): ApiError
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getParam(): string
    {
        return $this->param;
    }

    /**
     * @param string $param
     * @return ApiError
     */
    public function setParam(string $param = null): ApiError
    {
        $this->param = $param;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return ApiError
     */
    public function setMessage(string $message = null): ApiError
    {
        $this->message = $message;
        return $this;
    }




}