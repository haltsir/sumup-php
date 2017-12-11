<?php

namespace Sumup\Api\Service\App;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Mobile\Settings;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;

class AppSettingsService extends SumupService
{
    private $configuration;
    private $client;
    private $request;
    private $model;

    public function __construct(
        ConfigurationInterface $configuration,
        OAuthClientInterface $client,
        Request $request,
        Settings $model
    )
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->model = $model;

    }

    public function all()
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/app-settings'
                                 );

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return $this->model->hydrate(json_decode((string)$response->getBody(), true));
    }

    public function update(array $data)
    {
        $model = $this->model->hydrate($data);

        $request = $this->request->setMethod('PUT')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/app-settings'
                                 )
                                 ->setJson($model->serialize());

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return ($response->getStatusCode() === 204);

    }
}
