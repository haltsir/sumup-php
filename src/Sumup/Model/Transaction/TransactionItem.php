<?php

namespace Sumup\Api\Model\Transaction;

class TransactionItem
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $transactionId;

    /**
     * @var string
     */
    public $user;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var string
     */
    public $transactionCode;

    /**
     * @var string
     */
    public $productSummary;

    /**
     * @var int
     */
    public $installmentsCount;

    /**
     * @var string
     */
    public $paymentType;

    /**
     * @var  string
     */
    public $cardType;

    /**
     * @var int
     */
    public $payoutsTotal;

    /**
     * @var int
     */
    public $payoutsReceived;

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
}
