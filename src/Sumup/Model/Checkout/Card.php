<?php

namespace Sumup\Api\Model\Checkout;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Card
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY = [
        'last4Digits' => ['path' => 'last_4_digits'],
        'expiryYear' => ['path' => 'expiry_year'],
        'expiryMonth' => ['path' => 'expiry_month'],
        ];

    /**
     * @var string
     */
    public $last4Digits;

    /**
     * @var string
     */
    public $type;

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
