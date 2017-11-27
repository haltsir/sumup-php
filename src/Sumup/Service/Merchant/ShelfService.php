<?php

namespace Sumup\Api\Service\Merchant;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\ShelfFactory;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;
use Sumup\Api\Validator\AllowedArgumentsValidator;

class ShelfService extends SumupService
{
    const ALLOWED_ACCOUNT_OPTIONS = ['products'];

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
     * @var Collection
     */
    protected $collection;

    /**
     * @var ShelfFactory
     */
    protected $shelfFactory;

    public function __construct(
        ConfigurationInterface $configuration,
        OAuthClientInterface $client,
        Request $request,
        $allowedArgumentsValidator,
        Collection $collection,
        ShelfFactory $shelfFactory)
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->allowedArgumentsValidator = $allowedArgumentsValidator;
        $this->collection = $collection;
        $this->shelfFactory = $shelfFactory;
    }

    public function all($options = [])
    {
        if (false === $this->allowedArgumentsValidator::validate($options, self::ALLOWED_ACCOUNT_OPTIONS)) {
            throw new \Exception('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me/merchant-profile/shelves');

        if (sizeof($options) > 0) {
            $request->setQuery(implode('include[]=', $options));
        }

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return $this->shelfFactory->collect(json_decode((string)$response->getBody(), true));
    }

    public function get()
    {

    }

    public function create()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
