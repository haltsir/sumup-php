<?php

namespace Sumup\Api\Model\Customer;

use Sumup\Api\Model\Checkout\Card;
use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class PaymentInstrument
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY = ['card' => ['path' => 'card', 'type' => Card::class]];

    /**
     * @var string
     */
    public $token;

    /**
     * @var boolean
     */
    public $active;

    /**
     * @var string
     */
    public $type;

    /**
     * @var Card
     */
    public $card;

}