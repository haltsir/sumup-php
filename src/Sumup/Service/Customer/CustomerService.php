<?php

namespace Sumup\Api\Service\Customer;

use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\CustomerFactory;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;

class CustomerService extends SumupService
{
    /**
     * @var ConfigurationInterface
     */
    public $configuration;

    /**
     * @var OAuthClientInterface
     */
    public $client;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var CustomerFactory
     */
    public $customerFactory;

    public function __construct(ConfigurationInterface $configuration, OAuthClientInterface $client, Request $request,
                                CustomerFactory $customer)
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->customerFactory = $customer;
    }

    public function create(array $body)
    {
        $customer = $this->customerFactory->create()->hydrate($body);

        $request = $this->request->setMethod('POST')->setUri($this->configuration->getFullEndpoint() . '/customers')
                                 ->setJson($customer->serialize());

        $response = $this->client->request($request);

        $customerResult = $this->customerFactory->create();

        return $customerResult->hydrate(json_decode((string)$response->getBody(), true));
    }
}
