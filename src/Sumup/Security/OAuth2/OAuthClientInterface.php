<?php

namespace Sumup\Api\Security\OAuth2;

use Sumup\Api\Request\Request;

interface OAuthClientInterface
{
    public function request(array $scope, Request $request);
}
