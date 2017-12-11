<?php

namespace Sumup\Api\Service\Payout;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Exception\RequiredArgumentException;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\BankAccountFactory;
use Sumup\Api\Repository\Collection;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;

class BankAccountService extends SumupService
{
    const REQUIRED_DATA = ['bank_code', 'account_number', 'account_holder_name'];

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
     * @var BankAccountFactory
     */
    protected $bankAccountFactory;

    /**
     * @var string
     */
    protected $requiredArgumentsValidator;

    public function __construct(
        ConfigurationInterface $configuration,
        OAuthClientInterface $client,
        Request $request,
        BankAccountFactory $bankAccountFactory,
        string $requiredArgumentsValidator
    )
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->bankAccountFactory = $bankAccountFactory;
        $this->requiredArgumentsValidator = $requiredArgumentsValidator;
    }

    /**
     * Fetch all bank accounts.
     *
     * @return Collection
     */
    public function all()
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/bank-accounts'
                                 );

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return $this->bankAccountFactory->collect(json_decode((string)$response->getBody(), true));
    }

    /**
     * Fetch primary bank account.
     *
     * @return mixed
     */
    public function primary()
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri(
                                     $this->configuration->getFullEndpoint()
                                     . '/me/merchant-profile/bank-accounts?primary=true'
                                 );

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);
        $bankAccountsCollection = $this->bankAccountFactory->collect(json_decode((string)$response->getBody(), true));
        if (0 === sizeof($bankAccountsCollection->count()) || !isset($bankAccountsCollection[0])) {
            return null;
        }

        return $bankAccountsCollection[0];
    }

    public function create(array $data)
    {
        if (false === $this->requiredArgumentsValidator::validate($data, self::REQUIRED_DATA)) {
            throw new RequiredArgumentException('Missing required data provided to ' . __CLASS__);
        }

        $bankAccount = $this->bankAccountFactory->create()->hydrate($data);

        $request = $this->request->setMethod('POST')
                                 ->setUri($this->configuration->getFullEndpoint() .
                                          '/me/merchant-profile/bank-accounts')
                                 ->setJson($bankAccount->serialize());

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        $bankAccount = $this->bankAccountFactory->create();

        return $bankAccount->hydrate(json_decode((string)$response->getBody(), true));
    }
}
