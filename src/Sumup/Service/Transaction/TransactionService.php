<?php

namespace Sumup\Api\Service\Transaction;

use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Factory\TransactionItemFactory;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;

class TransactionService extends SumupService
{
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
     * @var TransactionItemFactory
     */
    protected $transactionItemFactory;

    public function __construct(
        Configuration $configuration,
        OAuthClientInterface $client,
        Request $request,
        TransactionItemFactory $transactionItemFactory
    )
    {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->request = $request;
        $this->transactionItemFactory = $transactionItemFactory;
    }

    public function history()
    {
        $response = $this->request->setMethod('GET')
                                  ->setUri(
                                      $this->configuration->getFullEndpoint()
                                      . '/me/transactions/history'
                                  );
    }

    public function details()
    {

    }
}
