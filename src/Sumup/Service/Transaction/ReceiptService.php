<?php

namespace Sumup\Api\Service\Transaction;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Exception\RequiredArgumentException;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Transaction\Receipt;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\Exception\InvalidArgumentException;
use Sumup\Api\Validator\AllowedArgumentsValidator;
use Sumup\Api\Validator\RequiredArgumentsValidator;

class ReceiptService
{
    /**
     * @var OAuthClientInterface
     */
    public $client;

    /**
     * @var Receipt
     */
    public $receipt;

    /**
     * @var Request
     */
    public $request;

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

    const ALLOWED_ACCOUNT_OPTIONS = ['mid'];

    const REQUIRED_DATA = ['mid'];

    public function __construct(ConfigurationInterface $configuration, OAuthClientInterface $client, Request $request,
                                Receipt $receipt, string $allowedArgumentsValidator,
                                $requiredArgumentsValidator)
    {
        $this->client = $client;
        $this->receipt = $receipt;
        $this->request = $request;
        $this->configuration = $configuration;
        $this->allowedArgumentsValidator = $allowedArgumentsValidator;
        $this->requiredArgumentsValidator = $requiredArgumentsValidator;

    }

    /**
     * Get Receipt
     *
     * @param string $transactionId
     * @param array $options
     * @throws InvalidArgumentException
     * @throws RequiredArgumentException
     * @return Receipt
     */
    public function get(string $transactionId, array $options)
    {
        if(false === $this->requiredArgumentsValidator::validate($options, self::REQUIRED_DATA)) {
            throw new RequiredArgumentException('Missing required data provided to ' . __CLASS__);
        }

        if(false === $this->allowedArgumentsValidator::validate($options, self::ALLOWED_ACCOUNT_OPTIONS)) {
            throw new InvalidArgumentException('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $request = $this->request
            ->setMethod('GET')
            ->setUri($this->configuration->getFullEndpoint() . '/receipts/' . $transactionId)
            ->setQuery($options);

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return $this->receipt->hydrate(json_decode((string)$response->getBody(), true));
    }
}
