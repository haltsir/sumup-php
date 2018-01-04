<?php

namespace Sumup\Api\Model\Checkout;

use Sumup\Api\Model\Transaction\Transaction;
use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Checkout
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY = [
        'transactions' => [
            'path' => 'transactions',
            'type' => 'array',
            'subtype' => Transaction::class
        ]
    ];

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $checkoutReference;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $payToEmail;

    /**
     * @var string
     */
    public $payFromEmail;

    /**
     * @var float
     */
    public $feeAmount;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $returnUrl;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $validUntil;

    /**
     * @var string
     */
    public $transactionCode;

    /**
     * @var string
     */
    public $transactionId;

    /**
     * @var array
     */
    public $transactions;

    /**
     * @var string
     */
    public $token;
}
