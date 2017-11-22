<?php

namespace Sumup\Api\Service\Merchant;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Merchant\Merchant;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;

class MerchantProfileService extends SumupService
{
    const ALLOWED_BANK_ACCOUNT_OPTIONS = ['primary'];

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var OAuthClientInterface
     */
    protected $client;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Merchant
     */
    protected $merchantModel;

    public function __construct(
        ConfigurationInterface $configuration,
        OAuthClientInterface $client,
        Request $request,
        Merchant $merchantModel
    )
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->merchantModel = $merchantModel;
    }

    public function get()
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me/merchant-profile');

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return $this->merchantModel->hydrate(json_decode((string)$response->getBody(), true));
    }

    public function update(array $body)
    {
//        $request = (new Request())->setMethod('PUT')
//                                  ->setUri($this->configuration->getFullEndpoint() . '/me/merchant-profile')
//                                  ->setBody($body);
//
//        $response = $this->client->request($request);
    }
}
