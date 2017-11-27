<?php

namespace Sumup\Api\Model\Product;

use Sumup\Api\Traits\HydratorTrait;

class Shelf
{
    use HydratorTrait;

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
