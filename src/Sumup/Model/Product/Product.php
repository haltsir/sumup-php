<?php

namespace Sumup\Api\Model\Product;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Product
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY = [
        'shelfId' => ['path' => 'shelf_id']
    ];

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $shelfId;

    /**
     * @var int
     */
    public $availability;

    /**
     * @var string
     */
    public $imageUrl;

    /**
     * @var int
     */
    public $stock;

    /**
     * @var string
     */
    public $subtitle;

    /**
     * @var string
     */
    public $title;

    /**
     * @var array
     */
    public $prices;

    /**
     * @var int
     */
    public $vatRateId;

    /**
     * @var int
     */
    public $colorId;
}
