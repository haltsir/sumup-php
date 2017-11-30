<?php

namespace Sumup\Api\Model\Product;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Price
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY = [
        'productId' => ['path' => 'product_id']
    ];

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $productId;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $net;
}
