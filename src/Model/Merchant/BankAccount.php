<?php

namespace Sumup\Api\Model\Merchant;

class BankAccount
{
    /**
     * @var string
     */
    public $bank_code;

    /**
     * @var string
     */
    public $branch_code;

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
