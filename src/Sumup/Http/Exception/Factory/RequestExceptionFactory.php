<?php

namespace Sumup\Api\Http\Exception\Factory;

use GuzzleHttp\Exception\ClientException;
use Sumup\Api\Errors\ApiError;
use Sumup\Api\Errors\ApiErrorContainer;
use Sumup\Api\Http\Exception\MultipleRequestExceptions;
use Sumup\Api\Http\Exception\UnknownRequestException;

class RequestExceptionFactory
{
    /**
     * Create exception from Guzzle Client Exception
     *
     * @param ClientException $clientException
     * @throws MultipleRequestExceptions
     * @throws UnknownRequestException
     */
    public function createFromClientException(ClientException $clientException)
    {
        $responseBody = $clientException->getResponse()->getBody();

        if (!$this->isValidJson($responseBody)) {
            throw new UnknownRequestException($responseBody);
        }

        $errorsArray = $this->getResponseAsArray($responseBody);

        throw new MultipleRequestExceptions($this->getErrors($errorsArray));

    }

    /**
     * @param $response
     * @return array|mixed
     */
    private function getResponseAsArray($response)
    {
        $result = json_decode((string)$response);

        if (!is_array($result)) {
            $result = [$result];
        }

        return $result;
    }

    /**
     * @param $strJson
     * @return bool
     */
    private function isValidJson($strJson)
    {
        json_decode($strJson);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * @param \stdClass $error
     * @return ApiError
     */
    private function formatError(\stdClass $error)
    {
        return new ApiError(
            $this->propertyChecker($error, 'error_code'),
            $this->propertyChecker($error, 'param'),
            $this->propertyChecker($error, 'message')
        );
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

        return new ApiErrorContainer($errorsArray);

    }

    /**
     * Check if object property exists and return it's value or null
     *
     * @param $obj
     * @param $property
     * @return null
     */
    private function propertyChecker($obj, $property)
    {
        if (property_exists($obj, $property)) {
            return $obj->$property;
        }

        return null;
    }
}