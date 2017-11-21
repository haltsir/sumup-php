<?php

namespace Sumup\Api\Service\Merchant;

use Sumup\Api\Http\Request;
use Sumup\Api\Service\SumupService;

class MerchantProfileDbaService extends SumupService
{
    public function get()
    {
//        $request = (new Request())->setMethod('GET')
//                                  ->setUri($this->configuration->getFullEndpoint() .
//                                           '/me/merchant-profile/doing-business-as');
//
//        $response = $this->client->request($request);
    }

    public function update(array $body)
    {
//        $request = (new Request())->setMethod('PUT')
//                                  ->setUri($this->configuration->getFullEndpoint() .
//                                           '/me/merchant-profile/doing-business-as')
//                                  ->setBody($body);
//
//        $response = $this->client->request($request);
    }
}
