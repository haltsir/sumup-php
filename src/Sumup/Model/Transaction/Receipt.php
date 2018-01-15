<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Model\Checkout\Card;
use Sumup\Api\Traits\HydratorTrait;

class Receipt
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = [
        'transaction' => ['path' => 'transaction', 'type' => Transaction::class],
        'merchantProfile' => ['path' => 'merchant_profile', 'type' => ReceiptMerchant::class],
        'card' => ['path' => 'card', 'type' => Card::class],
        'acquirerData' => ['path' => 'acquirer_data', 'type' => Acquirer::class],
        'envData' => ['path' => 'emv_data', 'type' => Emv::class],
    ];

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
     * @var Card
     */
    public $card;

    /**
     * @var Acquirer
     */
    public $acquirerData;

    /**
     * @var Emv
     */
    public $emvData;
}
