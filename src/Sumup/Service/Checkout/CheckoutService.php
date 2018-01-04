<?php

namespace Sumup\Api\Service\Checkout;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\CheckoutFactory;
use Sumup\Api\Model\Factory\CompletedCheckoutFactory;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\Exception\InvalidArgumentException;
use Sumup\Api\Service\SumupService;

class CheckoutService extends SumupService
{
    const REQUIRED_CREATE_DATA = ['checkout_reference', 'amount', 'currency', 'pay_to_email'];

    /**
     * @var Configuration
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
     * @var CheckoutFactory
     */
    protected $checkoutFactory;

    /**
     * @var CompletedCheckoutFactory
     */
    protected $completedCheckoutFactory;

    /**
     * @var string
     */
    protected $requiredArgumentsValidator;

    public function __construct(
        Configuration $configuration,
        OAuthClientInterface $client,
        Request $request,
        CheckoutFactory $checkoutFactory,
        CompletedCheckoutFactory $completedCheckoutFactory,
        $requiredArgumentsValidator
    )
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->checkoutFactory = $checkoutFactory;
        $this->completedCheckoutFactory = $completedCheckoutFactory;
        $this->requiredArgumentsValidator = $requiredArgumentsValidator;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function create(array $data)
    {
        if (false === $this->requiredArgumentsValidator::validate($data, self::REQUIRED_CREATE_DATA)) {
            throw new InvalidArgumentException('Missing required data provided to ' . __CLASS__);
        }

        $checkout = $this->checkoutFactory->create();
        $checkout->hydrate($data);

        $request = $this->request->setMethod('POST')
                                 ->setUri($this->configuration->getFullEndpoint() . '/checkouts')
                                 ->setJson($checkout->serialize());

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        $checkoutResult = $this->checkoutFactory->create();

        return $checkoutResult->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * @param string $id
     * @param array $data
     * @return mixed
     */
    public function complete(string $id, array $data)
    {
        $completedCheckout = $this->completedCheckoutFactory->create();
        $completedCheckout->hydrate($data);

        $request = $this->request->setMethod('PUT')
                                 ->setUri($this->configuration->getFullEndpoint() . '/checkouts/' . $id)
                                 ->setJson($completedCheckout->serialize());

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        $checkoutResult = $this->checkoutFactory->create();

        return $checkoutResult->hydrate(json_decode((string)$response->getBody(), true));
    }
}
