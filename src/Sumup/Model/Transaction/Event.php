<?php

namespace Sumup\Api\Model\Transaction;

class Event
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $transactionId;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $status;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var int
     */
    public $feeAmount;

    /**
     * @var string
     */
    public $receiptNo;
}