<?php

namespace Sumup\Api\Service\Customer;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\PaymentInstrumentFactory;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;

class PaymentInstrumentService extends SumupService
{
    const ALLOWED_OPTIONS = ['products'];

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
     * @var PaymentInstrumentFactory
     */
    protected $paymentInstrumentFactory;

    public function __construct(Configuration $configuration, OAuthClientInterface $client, Request $request,
                                PaymentInstrumentFactory $paymentInstrumentFactory)
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request =  $request;
        $this->paymentInstrumentFactory = $paymentInstrumentFactory;
    }

    /**
     * Create Payment Instrument
     *
     * @param string $customerId
     * @param $body
     * @return mixed
     */
    public function create(string $customerId,$body)
    {
        $paymentInstrument = $this->paymentInstrumentFactory->create()->hydrate($body);

        $request = $this->request
            ->setMethod('POST')
            ->setUri($this->configuration->getFullEndpoint() . '/customers/' . $customerId .'/payment-instruments')
            ->setJson($paymentInstrument->serialize());

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        $paymentInstrument = $this->paymentInstrumentFactory->create();

        return $paymentInstrument->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * Get Payment Instrument
     *
     * @param string $customerId
     * @return mixed
     */
    public function get(string $customerId)
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/customers/'
                                     . $customerId
                                     . '/payment-instruments'
                                 );

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        $paymentInstrument = $this->paymentInstrumentFactory->create();

        return $paymentInstrument->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * Disable Payment Instrument
     *
     * @param $customerId
     * @param $token
     * @return bool
     */
    public function disable($customerId,$token)
    {
        $request = $this->request->setMethod('DELETE')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . 'customers/'.$customerId.'/payment-instruments/'.$token
                                 );

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return ($response->getStatusCode() === 204);
    }
}
