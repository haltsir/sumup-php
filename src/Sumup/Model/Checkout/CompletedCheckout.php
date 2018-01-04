<?php

namespace Sumup\Api\Model\Checkout;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class CompletedCheckout
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY = [
        'paymentType' => ['path' => 'payment_type'],
        'boletoDetails' => [
            'path' => 'boleto_details',
            'type' => Boleto::class
        ],
        'card' => [
            'path' => 'card',
            'type' => Card::class
        ],
        'customerId' => ['path' => 'customer_id']
    ];

    const MAP_ENTITY_TO_JSON = [
        'card' => [
            'path' => 'card',
            'type' => Card::class
        ]
    ];

    /**
     * @var string
     */
    public $paymentType;

    /**
     * @var Boleto
     */
    public $boletoDetails;

    /**
     * @var int
     */
    public $installments;

    /**
     * @var Card
     */
    public $card;

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $customerId;
}
