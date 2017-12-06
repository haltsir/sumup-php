<?php

namespace Sumup\Api\Service\Merchant;

use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Exception\RequestException;
use Sumup\Api\Http\Exception\RequiredArgumentException;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\PriceFactory;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;

class PriceService extends SumupService
{
    const REQUIRED_DATA = ['net'];

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
     * @var string
     */
    protected $requiredArgumentsValidator;

    /**
     * @var PriceFactory
     */
    protected $priceFactory;

    public function __construct(
        ConfigurationInterface $configuration,
        OAuthClientInterface $client,
        Request $request,
        string $requiredArgumentsValidator,
        PriceFactory $priceFactory)
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->requiredArgumentsValidator = $requiredArgumentsValidator;
        $this->priceFactory = $priceFactory;
    }

    /**
     * @param $shelfId
     * @param $productId
     * @return Collection
     * @throws RequestException
     */
    public function all($shelfId, $productId)
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/shelves/'
                                     . (int)$shelfId
                                     . '/products/'
                                     . (int)$productId
                                     . '/prices'
                                 );
        try {
            /** @var ResponseInterface $response */
            $response = $this->client->request($request);
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            $content = json_decode((string)$response->getBody());
            throw new RequestException($content->message);
        }

        return $this->priceFactory->collect(json_decode((string)$response->getBody(), true));
    }

    /**
     * @param $shelfId
     * @param $productId
     * @param $priceId
     * @return mixed
     * @throws RequestException
     */
    public function get($shelfId, $productId, $priceId)
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/shelves/'
                                     . (int)$shelfId
                                     . '/products/'
                                     . (int)$productId
                                     . '/prices/'
                                     . (int)$priceId
                                 );

        try {
            /** @var ResponseInterface $response */
            $response = $this->client->request($request);
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            $content = json_decode((string)$response->getBody());
            throw new RequestException($content->message);
        }

        $price = $this->priceFactory->create();

        return $price->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * @param $shelfId
     * @param $productId
     * @param $data
     * @return mixed
     * @throws RequestException
     * @throws RequiredArgumentException
     */
    public function create($shelfId, $productId, $data)
    {
        if (false === $this->requiredArgumentsValidator::validate($data, self::REQUIRED_DATA)) {
            throw new RequiredArgumentException('Missing required data provided to ' . __CLASS__);
        }

        $request = $this->request->setMethod('POST')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/shelves/'
                                     . (int)$shelfId
                                     . '/products/'
                                     . (int)$productId
                                     . '/prices'
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

        $price = $this->priceFactory->create();

        return $price->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * @param $shelfId
     * @param $productId
     * @param $priceId
     * @param array $data
     * @return bool
     * @throws RequestException
     */
    public function update($shelfId, $productId, $priceId, $data = [])
    {
        $request = $this->request->setMethod('PUT')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/shelves/'
                                     . (int)$shelfId
                                     . '/products/'
                                     . (int)$productId
                                     . '/prices/'
                                     . (int)$priceId
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
     * @param $shelfId
     * @param $productId
     * @param $priceId
     * @return bool
     * @throws RequestException
     */
    public function delete($shelfId, $productId, $priceId)
    {
        $request = $this->request->setMethod('DELETE')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/shelves/'
                                     . (int)$shelfId
                                     . '/products/'
                                     . (int)$productId
                                     . '/prices/'
                                     . (int)$priceId
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
