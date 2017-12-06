<?php

namespace Sumup\Api\Service\Merchant;

use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\ShelfFactory;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\Exception\InvalidArgumentException;
use Sumup\Api\Service\SumupService;
use Sumup\Api\Validator\AllowedArgumentsValidator;
use Sumup\Api\Validator\RequiredArgumentsValidator;

class ShelfService extends SumupService
{
    const ALLOWED_OPTIONS = ['products'];
    const REQUIRED_DATA = ['name'];

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
     * @var AllowedArgumentsValidator
     */
    protected $allowedArgumentsValidator;

    /**
     * @var RequiredArgumentsValidator
     */
    protected $requiredArgumentsValidator;

    /**
     * @var ShelfFactory
     */
    protected $shelfFactory;

    public function __construct(
        ConfigurationInterface $configuration,
        OAuthClientInterface $client,
        Request $request,
        $allowedArgumentsValidator,
        $requiredArgumentsValidator,
        ShelfFactory $shelfFactory)
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->allowedArgumentsValidator = $allowedArgumentsValidator;
        $this->requiredArgumentsValidator = $requiredArgumentsValidator;
        $this->shelfFactory = $shelfFactory;
    }

    /**
     * Get all shelves.
     *
     * @param array $options
     * @return Collection
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function all(array $options = [])
    {
        if (false === $this->allowedArgumentsValidator::validate($options, self::ALLOWED_OPTIONS)) {
            throw new InvalidArgumentException('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me/merchant-profile/shelves');

        if (sizeof($options) > 0) {
            $request->setQuery(implode('include[]=', $options));
        }

        try {
            /** @var ResponseInterface $response */
            $response = $this->client->request($request);
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            $content = json_decode((string)$response->getBody());
            throw new RequestException($content->message);
        }

        return $this->shelfFactory->collect(json_decode((string)$response->getBody(), true));
    }

    /**
     * Get a single shelf.
     *
     * @param $id
     * @param array $options
     * @return mixed
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function get($id, array $options = [])
    {
        if (false === $this->allowedArgumentsValidator::validate($options, self::ALLOWED_OPTIONS)) {
            throw new InvalidArgumentException('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $request = $this->request->setMethod('GET')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/shelves/'
                                     . (int)$id
                                 );

        if (sizeof($options) > 0) {
            $request->setQuery(implode('include[]=', $options));
        }

        try {
            /** @var ResponseInterface $response */
            $response = $this->client->request($request);
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            $content = json_decode((string)$response->getBody());
            throw new RequestException($content->message);
        }

        $shelf = $this->shelfFactory->create();

        return $shelf->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * Create a shelf.
     *
     * @param array $data
     * @return mixed
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function create(array $data)
    {
        if (false === $this->requiredArgumentsValidator::validate($data, self::REQUIRED_DATA)) {
            throw new InvalidArgumentException('Missing required data provided to ' . __CLASS__);
        }

        $request = $this->request->setMethod('POST')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me/merchant-profile/shelves')
                                 ->setBody($data);

        try {
            /** @var ResponseInterface $response */
            $response = $this->client->request($request);
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            $content = json_decode((string)$response->getBody());
            throw new RequestException($content->message);
        }

        $shelf = $this->shelfFactory->create();

        return $shelf->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * Update a shelf.
     *
     * @param $id
     * @param array $data
     * @return bool
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function update($id, array $data)
    {
        if (0 !== $id && empty($id)) {
            throw new InvalidArgumentException('Provide a valid id to ' . __CLASS__);
        }

        $request = $this->request->setMethod('PUT')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/shelves/'
                                     . (int)$id
                                 )
                                 ->setBody($data);

        try {
            /** @var ResponseInterface $response */
            $response = $this->client->request($request);
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            $content = json_decode((string)$response->getBody());
            throw new RequestException($content->message);
        }

        return ($response->getStatusCode() === 204);
    }

    /**
     * Delete shelf.
     *
     * @param $id
     * @return bool
     * @throws RequestException
     */
    public function delete($id)
    {
        $request = $this->request->setMethod('DELETE')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/shelves/'
                                     . (int)$id
                                 );
        try {
            /** @var ResponseInterface $response */
            $response = $this->client->request($request);
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            $content = json_decode((string)$response->getBody());
            throw new RequestException($content->message);
        }

        return ($response->getStatusCode() === 204);
    }
}
