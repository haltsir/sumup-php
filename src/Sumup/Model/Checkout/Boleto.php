<?php

namespace Sumup\Api\Model\Checkout;

class Boleto
{
    /**
     * @var string
     */
    public $cpfCnpj;

    /**
     * @var string
     */
    public $payerName;

    /**
     * @var string
     */
    public $payerAddress;

    /**
     * @var string
     */
    public $payerCity;

    /**
     * @var string
     */
    public $payerStateCode;

    /**
     * @var string
     */
    public $payerPostCode;
}
