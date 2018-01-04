<?php

namespace Sumup\Api\Model\Checkout;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Card
{
    use HydratorTrait, SerializerTrait;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $number;

    /**
     * @var string
     */
    public $expiryYear;

    /**
     * @var string
     */
    public $expiryMonth;

    /**
     * @var string
     */
    public $cvv;
}
