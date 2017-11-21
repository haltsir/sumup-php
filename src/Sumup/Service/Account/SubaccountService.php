<?php

namespace Sumup\Api\Service\Account;

use Sumup\Api\Http\Request;
use Sumup\Api\Service\SumupService;
use Sumup\Api\Validator\AllowedArgumentsValidator;

class SubaccountService extends SumupService
{
    const ALLOWED_SUBACCOUNTS_OPTIONS = [];

    public function all(array $options = [])
    {
//        if (false === AllowedArgumentsValidator::validate($options, self::ALLOWED_SUBACCOUNTS_OPTIONS)) {
//            throw new \Exception('Invalid arguments provided to ' . __CLASS__ . '.');
//        }
//
//        $request = (new Request())->setMethod('GET')
//                                  ->setUri($this->configuration->getFullEndpoint() . '/me')
//                                  ->setQuery($options);
//
//        $response = $this->client->request($request);
    }

    public function create(array $body)
    {
//        $request = (new Request())->setMethod('POST')
//                                  ->setUri($this->configuration->getFullEndpoint() .
//                                           '/me/accounts')
//                                  ->setBody($body);
//
//        $response = $this->client->request($request);
    }

    public function update(string $subaccountId, $body)
    {
//        if (empty($operatorCode)) {
//            throw new \Exception('Subaccount ID is required.');
//        }
//
//        $request = (new Request())->setMethod('PUT')
//                                  ->setUri($this->configuration->getFullEndpoint() .
//                                           '/me/accounts/' . $operatorCode)
//                                  ->setBody($body);
//
//        $response = $this->client->request($request);
    }

    public function delete(string $subaccountId)
    {
//        if (empty($subaccountId)) {
//            throw new \Exception('Subaccount ID is required.');
//        }
//
//        $request = (new Request())->setMethod('DELETE')
//                                  ->setUri($this->configuration->getFullEndpoint() .
//                                           '/me/accounts/' . $subaccountId);
//
//        $response = $this->client->request($request);
    }
}
