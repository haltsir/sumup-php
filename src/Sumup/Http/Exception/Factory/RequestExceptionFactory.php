<?php

namespace Sumup\Api\Http\Exception\Factory;

use GuzzleHttp\Exception\ClientException;
use Sumup\Api\Exception\ApiError;
use Sumup\Api\Exception\ApiErrorContainer;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Http\Exception\UnknownResponseException;

class RequestExceptionFactory
{
    /**
     * @var ApiError
     */
    protected $apiError;

    /**
     * @var ApiErrorContainer
     */
    protected $apiErrorContainer;

    public function __construct(ApiError $apiError, ApiErrorContainer $apiErrorContainer)
    {
        $this->apiError = $apiError;
        $this->apiErrorContainer = $apiErrorContainer;
    }

    /**
     * Create exception from Guzzle Client Exception
     *
     * @param ClientException $clientException
     * @throws RequestException
     * @throws UnknownResponseException
     */
    public function createFromClientException(ClientException $clientException)
    {
        $responseBody = $clientException->getResponse()->getBody();

        if (!$this->isValidJson($responseBody)) {
            throw new UnknownResponseException($responseBody);
        }

        $errorsArray = $this->getResponseAsArray($responseBody);

        throw new RequestException($this->getErrors($errorsArray));
    }

    /**
     * @param $response
     * @return array|mixed
     */
    private function getResponseAsArray($response)
    {
        $result = json_decode((string)$response);

        return is_array($result) ? $result : [$result];
    }

    /**
     * @param string $json
     * @return bool
     */
    private function isValidJson(string $json = null)
    {
        json_decode($json);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * @param \stdClass $error
     * @return ApiError
     */
    private function formatError(\stdClass $error)
    {
        return $this->apiError
            ->setMessage(property_exists($error, 'message') ? $error->message : null)
            ->setParam(property_exists($error, 'param') ? $error->param : null)
            ->setErrorCode(property_exists($error, 'error_code') ? $error->error_code : null);
    }

    /**
     * @param array $errors
     * @return ApiErrorContainer
     */
    private function getErrors(array $errors)
    {
        $errorsArray = [];

        foreach ($errors as $error) {
            $errorsArray[] = $this->formatError($error);
        }
        return $this->apiErrorContainer->createFromArray($errorsArray);
    }
}