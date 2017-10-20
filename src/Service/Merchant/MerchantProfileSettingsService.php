<?php

namespace Sumup\Api\Service\Merchant;

use Sumup\Api\Request\Request;
use Sumup\Api\Service\SumupService;

class MerchantProfileSettingsService extends SumupService
{
    public function all()
    {
        $scope = ['user.payout-settings'];
        $request = (new Request())->setMethod('GET')
                                  ->setUri($this->configuration->getFullEndpoint() .
                                           '/me/merchant-profile/settings');

        $response = $this->oAuthClient->request($scope, $request);
    }

    public function update(array $body)
    {
        $scope = ['user.payout-settings'];
        $request = (new Request())->setMethod('GET')
                                  ->setUri($this->configuration->getFullEndpoint() .
                                           '/me/merchant-profile/settings')
                                  ->setBody($body);

        $response = $this->oAuthClient->request($scope, $request);
    }
}
