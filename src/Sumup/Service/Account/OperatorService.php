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
     * Get All Operators
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
     * Get Operator
     *
     * @param string $id
     * @return mixed
     * @throws RequiredArgumentException
     */
    public function get(string $id)
    {
        if (empty($id)) {
            throw new RequiredArgumentException('Operator id is required.');
        }

        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me/accounts/' . $id);

        $response = $this->client->request($request);

        $operator = $this->operatorFactory->create();

        return $operator->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * Create Operator
     *
     * @param array $body
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function create(array $body)
    {
        $operator = $this->operatorFactory
            ->create()
            ->hydrate($body);

        if (false === $this->requiredArgumentsValidator::validate($body, self::REQUIRED_ARGS)) {
            throw new InvalidArgumentException('Missing required data provided to ' . __CLASS__);
        }

        $request = $this->request->setMethod('POST')
                                 ->setUri($this->configuration->getFullEndpoint() .
                                          '/me/accounts')
                                 ->setJson($operator->serialize());

        $response = $this->client->request($request);
        return $operator->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * Update Operator
     *
     * @param string $id
     * @param $body
     * @return bool
     * @throws RequiredArgumentException
     */
    public function update(string $id, $body)
    {
        if (empty($id)) {
            throw new RequiredArgumentException('Operator id is required.');
        }

        $operator = $this->operatorFactory
            ->create()
            ->hydrate($body);

        $request = $this->request
            ->setMethod('PUT')
            ->setUri($this->configuration->getFullEndpoint() . '/me/accounts/' . $id)
            ->setJson($operator->serialize());

        $response = $this->client->request($request);

        return ($response->getStatusCode() === 200);

    }

    /**
     * Disable Operator
     *
     * @param string $id
     * @return bool
     * @throws RequiredArgumentException
     */
    public function disable(string $id)
    {
        if (empty($id)) {
            throw new RequiredArgumentException('Operator id is required.');
        }

        $request = $this->request
            ->setMethod('DELETE')
            ->setUri($this->configuration->getFullEndpoint() . '/me/accounts/' . $id);

        $response = $this->client->request($request);

        return ($response->getStatusCode() === 200);
    }
}
