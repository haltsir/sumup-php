<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Traits\HydratorTrait;

class TransactionProduct
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = [
        'totalPrice' => ['path' => 'total_price'],
];

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $price;

    /**
     * @var int
     */
    public $vatRate;

    /**
     * @var int
     */
    public $singleVatAmount;

    /**
     * @var int
     */
    public $priceWithVat;

    /**
     * @var int
     */
    public $vatAmount;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var int
     */
    public $totalPrice;

    /**
     * @var int
     */
    public $totalWithVat;
}
