<?php

namespace Sumup\Api\Model\Transaction;

class TransactionProduct
{
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
