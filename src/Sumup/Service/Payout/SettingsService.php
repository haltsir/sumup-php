<?php

namespace Sumup\Api\Service\Payout;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Payout\Settings;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;

class SettingsService
{
    protected $configuration;
    protected $client;
    protected $request;
    protected $settingsModel;

    public function __construct(
        Configuration $configuration,
        OAuthClientInterface $client,
        Request $request,
        Settings $settingsModel
    )
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->settingsModel = $settingsModel;
    }

    public function get()
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/settings'
                                 );

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return $this->settingsModel->hydrate(json_decode((string)$response->getBody(), true));
    }

    public function update(array $data)
    {
        $settings = $this->settingsModel->hydrate($data);

        $request = $this->request->setMethod('PUT')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/settings'
                                 )
                                 ->setJson($settings->serialize());

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return (200 === $response->getStatusCode());
    }
}
