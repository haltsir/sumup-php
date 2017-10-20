<?php

namespace Sumup\Api\Service\Account;

use Sumup\Api\Request\Request;
use Sumup\Api\Service\SumupService;
use Sumup\Api\Validator\AllowedArgumentsValidator;

class AccountService extends SumupService
{
    const ALLOWED_ACCOUNT_OPTIONS = ['include'];
    const ALLOWED_SUBACCOUNTS_OPTIONS = ['include'];

    public function get(array $options = [])
    {
        if (false === AllowedArgumentsValidator::validate($options, self::ALLOWED_ACCOUNT_OPTIONS)) {
            throw new \Exception('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $scope = ['user.profile', 'user.profile_readonly'];
        $request = (new Request())->setMethod('GET')
                                  ->setUri($this->configuration->getFullEndpoint() . '/me')
                                  ->setQuery($options);

        $response = $this->oAuthClient->request($scope, $request);
    }
}
