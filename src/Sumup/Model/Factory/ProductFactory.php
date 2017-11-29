<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Product\Product;
use Sumup\Api\Repository\Collection;

class ProductFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var Product
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Product $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}
