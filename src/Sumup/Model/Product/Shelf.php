<?php

namespace Sumup\Api\Model\Product;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Shelf
{
    use HydratorTrait, SerializerTrait;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $order;

    /**
     * @var array
     */
    public $products;
}
