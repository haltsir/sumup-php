<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Model\Merchant\CardApplication;

class Receipt
{
    /**
     * @var Transaction
     */
    public $transaction;

    /**
     * @var ReceiptMerchant
     */
    public $merchantProfile;

    /**
     * @var string
     */
    public $signature;

    /**
     * @var string
     */
    public $receiptNumber;

    /**
     * @var CardApplication
     */
    public $cardApplication;

    /**
     * @var Acquirer
     */
    public $acquirerData;

    /**
     * @var Emv
     */
    public $emvData;
}
