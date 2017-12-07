<?php

namespace Sumup\Api\Service\Account;

use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Exception\RequiredArgumentException;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\OperatorFactory;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\Exception\InvalidArgumentException;
use Sumup\Api\Service\SumupService;
use Sumup\Api\Validator\RequiredArgumentsValidator;

class OperatorService extends SumupService
{
    /**
     * @var RequiredArgumentsValidator
     */
    protected $requiredArgumentsValidator;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var OAuthClientInterface
     */
    protected $client;

    /**
     * @var OperatorFactory
     */
    protected $operatorFactory;

    /**
     * @var Collection
     */
    protected $collection;

    const REQUIRED_ARGS = ['username', 'password'];

    /**
     * OperatorService constructor.
     *
     * @param OperatorFactory $operatorFactory
     * @param Collection $collection
     * @param $requiredArgumentsValidator
     * @param Request $request
     * @param ConfigurationInterface $configuration
     * @param OAuthClientInterface $client
     */
    public function __construct(OperatorFactory $operatorFactory, Collection $collection,
                                $requiredArgumentsValidator,
                                Request $request, ConfigurationInterface $configuration, OAuthClientInterface $client)
    {
        $this->requiredArgumentsValidator = $requiredArgumentsValidator;
        $this->request = $request;
        $this->configuration = $configuration;
        $this->client = $client;
        $this->operatorFactory = $operatorFactory;
    }

    /**
     * Get All SubAccounts
     *
     * @return Collection
     */
    public function all()
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me/accounts');

        $response = $this->client->request($request);

        return $this->operatorFactory->collect(json_decode((string)$response->getBody(), true));
    }

    /**
     * Get SubAccount
     *
     * @param string $operatorCode
     * @return mixed
     * @throws RequiredArgumentException
     */
    public function get(string $operatorCode)
    {
        if (empty($operatorCode)) {
            throw new RequiredArgumentException('SubAccount ID is required.');
        }

        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me/accounts/' . $operatorCode);

        $response = $this->client->request($request);

        $subAccount = $this->operatorFactory->create();

        return $subAccount->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * Create SubAccount
     *
     * @param array $body
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function create(array $body)
    {
        $subAccount = $this->operatorFactory
            ->create()
            ->hydrate($body);

        if (false === $this->requiredArgumentsValidator::validate($body, self::REQUIRED_ARGS)) {
            throw new InvalidArgumentException('Missing required data provided to ' . __CLASS__);
        }

        $request = $this->request->setMethod('POST')
                                 ->setUri($this->configuration->getFullEndpoint() .
                                          '/me/accounts')
                                 ->setJson($subAccount->serialize());

        $response = $this->client->request($request);
        return $subAccount->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * Update SubAccount
     *
     * @param string $operatorCode
     * @param $body
     * @return bool
     * @throws RequiredArgumentException
     */
    public function update(string $operatorCode, $body)
    {
        if (empty($operatorCode)) {
            throw new RequiredArgumentException('SubAccount ID is required.');
        }

        $subAccount = $this->operatorFactory
            ->create()
            ->hydrate($body);

        $request = $this->request
            ->setMethod('PUT')
            ->setUri($this->configuration->getFullEndpoint() . '/me/accounts/' . $operatorCode)
            ->setJson($subAccount->serialize());

        $response = $this->client->request($request);

        return ($response->getStatusCode() === 200);

    }

    /**
     * Disable SubAccount
     *
     * @param string $operatorCode
     * @return bool
     * @throws RequiredArgumentException
     */
    public function disable(string $operatorCode)
    {
        if (empty($operatorCode)) {
            throw new RequiredArgumentException('SubAccount ID is required.');
        }

        $request = $this->request
            ->setMethod('DELETE')
            ->setUri($this->configuration->getFullEndpoint() . '/me/accounts/' . $operatorCode);

        $response = $this->client->request($request);

        return ($response->getStatusCode() === 200);
    }
}
