<?php

namespace Sumup\Api\Service\Account;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Model\Merchant\Account;
use Sumup\Api\Http\Request;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\Exception\InvalidArgumentException;
use Sumup\Api\Service\SumupService;
use Sumup\Api\Validator\AllowedArgumentsValidator;

class AccountService extends SumupService
{
    const ALLOWED_ACCOUNT_OPTIONS     = ['include'];
    const ALLOWED_SUBACCOUNTS_OPTIONS = ['include'];

    /**
     * @var Account
     */
    protected $accountModel;

    /**
     * @var AllowedArgumentsValidator
     */
    protected $allowedArgumentsValidator;

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
     * AccountService constructor.
     * @param Request $request
     * @param ConfigurationInterface $configuration
     * @param OAuthClientInterface $client
     * @param Account $account
     * @param string $allowedArgumentsValidator
     */
    public function __construct(
        ConfigurationInterface $configuration,
        OAuthClientInterface $client,
        Request $request,
        Account $account,
        string $allowedArgumentsValidator
    )
    {
        $this->accountModel = $account;
        $this->allowedArgumentsValidator = $allowedArgumentsValidator;
        $this->request = $request;
        $this->configuration = $configuration;
        $this->client = $client;
    }

    public function get(array $options = [])
    {
        if (false === $this->allowedArgumentsValidator::validate($options, self::ALLOWED_ACCOUNT_OPTIONS)) {
            throw new InvalidArgumentException('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me')
                                 ->setQuery($options);
        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return $this->accountModel->hydrate(json_decode((string)$response->getBody(), true));
    }
}
