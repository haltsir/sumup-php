<?php

namespace Sumup\Api\Service\Account;

use Sumup\Api\Http\Request;
use Sumup\Api\Service\SumupService;

class PersonalProfileService extends SumupService
{
    public function get()
    {
//        $request = (new Request())->setMethod('GET')
//                                  ->setUri($this->configuration->getFullEndpoint() . '/me/personal-profile');
//
//        $response = $this->client->request($request);
    }

    public function update($body)
    {
//        $request = (new Request())->setMethod('PUT')
//                                  ->setUri($this->configuration->getFullEndpoint() . '/me/personal-profile')
//                                  ->setBody($body);
//
//        $response = $this->client->request($request);
    }
}
