<?php

namespace Sumup\Api\Service\Merchant;

use Sumup\Api\Http\Request;
use Sumup\Api\Service\SumupService;

class MerchantProfileService extends SumupService
{
    const ALLOWED_BANK_ACCOUNT_OPTIONS = ['primary'];

    public function get()
    {
//        $request = (new Request())->setMethod('GET')
//                                  ->setUri($this->configuration->getFullEndpoint() . '/me/merchant-profile');
//
//        $response = $this->client->request($request);
    }

    public function update(array $body)
    {
//        $request = (new Request())->setMethod('PUT')
//                                  ->setUri($this->configuration->getFullEndpoint() . '/me/merchant-profile')
//                                  ->setBody($body);
//
//        $response = $this->client->request($request);
    }
}
