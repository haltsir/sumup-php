<?php

namespace Sumup\Api\Service\Merchant;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Merchant\Business;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;

class BusinessService extends SumupService
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var OAuthClientInterface
     */
    private $client;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $requiredArgumentsValidator;

    /**
     * @var Business
     */
    private $businessModel;

    public function __construct(
        Configuration $configuration,
        OAuthClientInterface $client,
        Request $request,
        string $requiredArgumentsValidator,
        Business $businessModel
    )
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->requiredArgumentsValidator = $requiredArgumentsValidator;
        $this->businessModel = $businessModel;
    }

    /**
     * Get current business face.
     *
     * @return Business
     */
    public function get()
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/doing-business-as'
                                 );

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return $this->businessModel->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * Update business face.
     *
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        $business = $this->businessModel->hydrate($data);

        $request = $this->request->setMethod('PUT')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/doing-business-as'
                                 )
                                 ->setJson($business->serialize());

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return (200 === $response->getStatusCode());
    }
}
