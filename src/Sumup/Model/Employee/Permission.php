<?php

namespace Sumup\Api\Model\Employee;

class Permission
{
    /**
     * @var bool
     */
    public $createMotoPayments;

    /**
     * @var bool
     */
    public $fullTransactionHistoryView;

    /**
     * @var bool
     */
    public $refundTransactions;

    /**
     * @var bool
     */
    public $createReferral;
}
