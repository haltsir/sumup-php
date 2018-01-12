<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Traits\HydratorTrait;

class TransactionHistory
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = [
        'items' => [
            'path' => 'items',
            'type' => 'array',
            'subtype' => TransactionItem::class
        ],
        'links' => [
            'path' => 'links',
            'type' => 'array',
            'subtype' => TransactionLink::class
        ]
    ];

    /**
     * @var array
     */
    public $items;

    /**
     * @var array
     */
    public $links;
}
