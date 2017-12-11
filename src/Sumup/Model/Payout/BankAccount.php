<?php

namespace Sumup\Api\Model\Payout;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class BankAccount
{
    use HydratorTrait, SerializerTrait;

    /**
     * @var string
     */
    public $bankCode;

    /**
     * @var string
     */
    public $branchCode;

    /**
     * @var string
     */
    public $swift;

    /**
     * @var string
     */
    public $accountNumber;

    /**
     * @var string
     */
    public $iban;

    /**
     * @var string
     */
    public $accountType;

    /**
     * @var string
     */
    public $accountHolderName;

    /**
     * @var string
     */
    public $status;

    /**
     * @var bool
     */
    public $primary;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $bankName;
}
