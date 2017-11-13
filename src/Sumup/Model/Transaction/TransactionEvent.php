<?php

namespace Sumup\Api\Model\Transaction;

class TransactionEvent
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $eventType;

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
    public $dueDate;

    /**
     * @var string
     */
    public $date;

    /**
     * @var int
     */
    public $installmentNumber;

    /**
     * @var string
     */
    public $timestamp;
}
