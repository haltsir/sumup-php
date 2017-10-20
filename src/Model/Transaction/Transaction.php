<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Model\Merchant\Card;
use Sumup\Api\Model\Merchant\ElvAccount;

class Transaction
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $transactionCode;

    /**
     * @var string
     */
    public $foreignTransactionId;

    /**
     * @var string
     */
    public $merchantCode;

    /**
     * @var string
     */
    public $username;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var int
     */
    public $vatAmount;

    /**
     * @var int
     */
    public $tipAmount;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var int
     */
    public $lat;

    /**
     * @var int
     */
    public $lon;

    /**
     * @var int
     */
    public $horizontalAccuracy;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $paymentType;

    /**
     * @var string
     */
    public $simplePaymentType;

    /**
     * @var string
     */
    public $entryMode;

    /**
     * @var string
     */
    public $verificationMethod;

    /**
     * @var Card
     */
    public $card;

    /**
     * @var ElvAccount
     */
    public $elvAccount;

    /**
     * @var string
     */
    public $productSummary;

    /**
     * @var string
     */
    public $localTime;

    /**
     * @var string
     */
    public $payoutDate;

    /**
     * @var string
     */
    public $payoutPlan;

    /**
     * @var string
     */
    public $payoutType;

    /**
     * @var int
     */
    public $installmentsCount;

    /**
     * @var string
     */
    public $processAs;

    /**
     * @var TransactionProduct
     */
    public $products;

    /**
     * @var
     */
    public $transactionEvents;

    /**
     * @var string
     */
    public $simpleStatus;

    /**
     * @var Event
     */
    public $events;

    /**
     * @var int
     */
    public $payoutsReceived;

    /**
     * @var int
     */
    public $payoutsTotal;

    /**
     * @var Location
     */
    public $location;

    /**
     * @var bool
     */
    public $taxEnabled;

    /**
     * @var string
     */
    public $authCode;

    /**
     * @var int
     */
    public $internalId;
}
