<?php

namespace Sumup\Api\Service\Merchant;

use Sumup\Api\Request\Request;
use Sumup\Api\Service\SumupService;

class MerchantProfileDbaService extends SumupService
{
    public function get()
    {
        $scope = ['user.profile', 'user.profile_readonly'];
        $request = (new Request())->setMethod('GET')
                                  ->setUri($this->configuration->getFullEndpoint() .
                                           '/me/merchant-profile/doing-business-as');

        $response = $this->oAuthClient->request($scope, $request);
    }

    public function update(array $body)
    {
        $scope = ['user.profile'];
        $request = (new Request())->setMethod('PUT')
                                  ->setUri($this->configuration->getFullEndpoint() .
                                           '/me/merchant-profile/doing-business-as')
                                  ->setBody($body);

        $response = $this->oAuthClient->request($scope, $request);
    }
}
