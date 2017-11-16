<?php

namespace Sumup\Api\Service\Account;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Model\Merchant\Account;
use Sumup\Api\Request\Request;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;
use Sumup\Api\Validator\AllowedArgumentsValidator;

class AccountService extends SumupService
{
    const ALLOWED_ACCOUNT_OPTIONS = ['include'];
    const ALLOWED_SUBACCOUNTS_OPTIONS = ['include'];

    /**
     * AccountService constructor.
     * @param OAuthClientInterface $client
     * @todo Place Request and Account dependencies here
     */
    public function __construct(OAuthClientInterface $client)
    {
        parent::__construct($client);
    }

    public function get(array $options = [])
    {
        if (false === AllowedArgumentsValidator::validate($options, self::ALLOWED_ACCOUNT_OPTIONS)) {
            throw new \Exception('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $request = (new Request())->setMethod('GET')
                                  ->setUri($this->configuration->getFullEndpoint() . '/me')
                                  ->setQuery($options);

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return (new Account())->hydrate(json_decode((string)$response->getBody(), true));
    }
}
