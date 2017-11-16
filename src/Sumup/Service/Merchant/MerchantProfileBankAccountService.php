<?php

namespace Sumup\Api\Service\Merchant;

use Sumup\Api\Request\Request;
use Sumup\Api\Service\SumupService;
use Sumup\Api\Validator\AllowedArgumentsValidator;

class MerchantProfileBankAccountService extends SumupService
{
    const ALLOWED_BANK_ACCOUNT_OPTIONS = [];

    public function all(array $options = [])
    {
        if (false === AllowedArgumentsValidator::validate($options, self::ALLOWED_BANK_ACCOUNT_OPTIONS)) {
            throw new \Exception('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $request = (new Request())->setMethod('GET')
                                  ->setUri($this->configuration->getFullEndpoint() .
                                           '/me/merchant-profile/bank-accounts')
                                  ->setQuery($options);

        $response = $this->client->request($request);
    }

    public function create(array $body)
    {
        $request = (new Request())->setMethod('POST')
                                  ->setUri($this->configuration->getFullEndpoint() .
                                           '/me/merchant-profile/bank-accounts')
                                  ->setBody($body);

        $response = $this->client->request($request);
    }
}
