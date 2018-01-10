<?php

namespace Sumup\Api\Service\Transaction;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\TransactionFactory;
use Sumup\Api\Model\Factory\TransactionHistoryFactory;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\Exception\InvalidArgumentException;
use Sumup\Api\Service\SumupService;

class TransactionService extends SumupService
{
    const ALLOWED_HISTORY_OPTIONS = ['order', 'limit', 'users[]', 'geo_coordinates', 'readers[]', 'statuses[]',
                                     'payment_types[]', 'types[]', 'changes_since', 'newest_time', 'newest_ref',
                                     'newest_incl', 'oldest_time', 'oldest_ref', 'oldest_incl'];
    const ALLOWED_DETAILS_OPTIONS = ['id', 'internal_id', 'transaction_code', 'foreign_transaction_id',
                                     'client_transaction_id', 'event_statuses[]', 'event_types[]'];

    /**
     * @var Configuration
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
     * @var TransactionFactory
     */
    protected $transactionFactory;

    /**
     * @var TransactionHistoryFactory
     */
    protected $transactionHistoryFactory;

    /**
     * @var string
     */
    protected $allowedArgumentsValidator;

    public function __construct(
        Configuration $configuration,
        OAuthClientInterface $client,
        Request $request,
        TransactionFactory $transactionFactory,
        TransactionHistoryFactory $transactionHistoryFactory,
        string $allowedArgumentsValidator
    )
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->transactionFactory = $transactionFactory;
        $this->transactionHistoryFactory = $transactionHistoryFactory;
        $this->allowedArgumentsValidator = $allowedArgumentsValidator;
    }

    /**
     * @param array $options
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function history(array $options = [])
    {
        if (false === $this->allowedArgumentsValidator::validate($options, self::ALLOWED_HISTORY_OPTIONS)) {
            throw new InvalidArgumentException('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me/transactions/history');

        if (sizeof($options) > 0) {
            $request->setQuery($options);
        }

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        $history = $this->transactionHistoryFactory->create();

        return $history->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * @param array $options
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function details(array $options = [])
    {
        if (false === $this->allowedArgumentsValidator::validate($options, self::ALLOWED_DETAILS_OPTIONS)) {
            throw new InvalidArgumentException('Invalid arguments provided to ' . __CLASS__ . '.');
        }

        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me/transactions');

        if (sizeof($options) > 0) {
            $request->setQuery($options);
        }

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        $transaction = $this->transactionFactory->create();

        return $transaction->hydrate(json_decode((string)$response->getBody(), true));
    }
}
