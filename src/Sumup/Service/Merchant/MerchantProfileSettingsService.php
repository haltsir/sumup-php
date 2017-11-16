<?php

namespace Sumup\Api\Service\Merchant;

use Sumup\Api\Request\Request;
use Sumup\Api\Service\SumupService;

class MerchantProfileSettingsService extends SumupService
{
    public function all()
    {
        $request = (new Request())->setMethod('GET')
                                  ->setUri($this->configuration->getFullEndpoint() .
                                           '/me/merchant-profile/settings');

        $response = $this->client->request($request);
    }

    public function update(array $body)
    {
        $request = (new Request())->setMethod('GET')
                                  ->setUri($this->configuration->getFullEndpoint() .
                                           '/me/merchant-profile/settings')
                                  ->setBody($body);

        $response = $this->client->request($request);
    }
}
