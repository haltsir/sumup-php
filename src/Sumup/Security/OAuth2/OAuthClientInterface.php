<?php

namespace Sumup\Api\Security\OAuth2;

use Sumup\Api\Http\Request;

interface OAuthClientInterface
{
    public function request(Request $request);
}
