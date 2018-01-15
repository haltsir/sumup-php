<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Traits\HydratorTrait;

class TransactionEvent
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = [
                'transactionId'=>['path'=>'transaction_id'],
                'eventType'=>['path'=>'type'],
                'amount'=>['path'=>'amount'],
                'freeAmount'=>['path'=>'free_amount'],
                'installmentNumber'=>['path'=>'installment_number'],
                'deductedAmount'=>['path'=>'deducted_amount'],
                'deductedFreeAmount'=>['path'=>'deducted_fee_amount'],
    ];

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
    public $eventType;

    /**
     * @var string
     */
    public $status;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var float
     */
    public $freeAmount;

    /**
     * @var int
     */
    public $installmentNumber;

    /**
     * @var float
     */
    public $deductedAmount;

    /**
     * @var float
     */
    public $deductedFreeAmount;

}
