<?php

namespace Sumup\Api\Service\Transaction;

use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\RefundFactory;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\Exception\InvalidArgumentException;
use Sumup\Api\Service\SumupService;
use Sumup\Api\Validator\AllowedArgumentsValidator;
use Sumup\Api\Validator\RequiredArgumentsValidator;

class RefundService extends SumupService
{
    /**
     * @var OAuthClientInterface
     */
    public $client;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var RefundFactory
     */
    public $refundFactory;

    /**
     * @var ConfigurationInterface
     */
    public $configuration;

    /**
     * @var AllowedArgumentsValidator
     */
    protected $allowedArgumentsValidator;

    /**
     * @var RequiredArgumentsValidator
     */
    protected $requiredArgumentsValidator;

    const ALLOWED_ACCOUNT_OPTIONS = ['amount'];

    public function __construct(ConfigurationInterface $configuration, OAuthClientInterface $client,
                                RefundFactory $refundFactory, Request $request,
                                string $allowedArgumentsValidator)
    {
        $this->client = $client;
        $this->request = $request;
        $this->configuration = $configuration;
        $this->allowedArgumentsValidator = $allowedArgumentsValidator;
        $this->refundFactory = $refundFactory;
    }

    /**
     * Refund a transaction with the given id
     *
     * @param string $transactionId
     * @param $body
     * @return bool
     * @throws InvalidArgumentException
     */
    public function refund($transactionId = null, $body)
    {
        if (false === $this->allowedArgumentsValidator::validate($body, self::ALLOWED_ACCOUNT_OPTIONS)) {
            throw new InvalidArgumentException('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $refundModel = $this->refundFactory->create()->hydrate($body);

        $request = $this->request->setMethod('POST')->setUri($this->configuration->getFullEndpoint() . '/me/refund/' .
                                                             $transactionId)
                                 ->setJson($refundModel->serialize());

        $response = $this->client->request($request);

        return ($response->getStatusCode() === 204);
    }
}
