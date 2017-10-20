<?php

namespace Sumup\Api\Security\OAuth2;

use GuzzleHttp\Client;
use Sumup\Api\Request\Request;
use Sumup\Api\Security\Authentication\TokenStorageInterface;

class OAuthClient
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var Client
     */
    protected $httpClient;

    public function __construct(
        TokenStorageInterface $tokenStorage
    )
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param array $scope
     * @param Request $request
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function request(array $scope, Request $request)
    {
        $options = [
            'headers' => $this->tokenStorage->getToken()
        ];

        if ('POST' === $request->getMethod()) {
            $body = $request->getBody();
            $request->setBody($body + ['scope' => implode(',', $scope)]);
        }

        return $request->send($options);
    }
}
