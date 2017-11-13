<?php

namespace Sumup\Api\Model\Product;

class Product
{
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
