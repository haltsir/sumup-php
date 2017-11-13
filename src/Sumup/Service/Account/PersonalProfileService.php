<?php

namespace Sumup\Api\Service\Account;

use Sumup\Api\Request\Request;
use Sumup\Api\Service\SumupService;

class PersonalProfileService extends SumupService
{
    public function get()
    {
        $scope = ['user.profile', 'user.profile_readonly'];
        $request = (new Request())->setMethod('GET')
                                  ->setUri($this->configuration->getFullEndpoint() . '/me/personal-profile');

        $response = $this->oAuthClient->request($scope, $request);
    }

    public function update($body)
    {
        $scope = ['user.profile'];
        $request = (new Request())->setMethod('PUT')
                                  ->setUri($this->configuration->getFullEndpoint() . '/me/personal-profile')
                                  ->setBody($body);

        $response = $this->oAuthClient->request($scope, $request);
    }
}
