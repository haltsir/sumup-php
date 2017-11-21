<?php

namespace Sumup\Api;

use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Exception\SumupClientException;
use Sumup\Api\Security\Factory\OAuthClientFactory;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;

class SumupClient
{
    /**
     * @var SumupContainer
     */
    protected $container;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var OAuthClientInterface
     */
    protected $client;

    /**
     * SumupClient constructor.
     *
     * @param ConfigurationInterface|null $configuration
     * @param SumupContainer|null $container
     */
    public function __construct(ConfigurationInterface $configuration = null, SumupContainer $container = null)
    {
        $this->container = ($container instanceof SumupContainer ? $container : new SumupContainer());
        $this->configuration = ($configuration instanceof ConfigurationInterface
            ? $configuration
            : new $this->container['configuration']);

        if (empty($this->configuration->getApiEndpoint()) && $this->container->has('api.endpoint')) {
            $this->configuration->setApiEndpoint($this->container['api.endpoint']);
        }

        if (empty($this->configuration->getApiVersion()) && $this->container->has('api.version')) {
            $this->configuration->setApiVersion($this->container['api.version']);
        }

        $this->container['configuration'] = $this->configuration;

        /** @var OAuthClientFactory $clientFactory */
        $clientFactory = $this->container['oauth.factory.client'];
        $this->client = $clientFactory->create($this->configuration, $this->container['http.client'],
                                               $this->container['cache.pool']);
    }

    /**
     * @return OAuthClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return SumupContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param $service
     * @return SumupService
     * @throws SumupClientException
     */
    public function createService($service)
    {
        $internalServiceName = strtolower(trim($service));

        if (false === $this->container->has($internalServiceName . '.service')) {
            throw new SumupClientException(sprintf('Services %s does not exist', $service));
        }

        return $this->container[$internalServiceName . '.service'];
    }
}
