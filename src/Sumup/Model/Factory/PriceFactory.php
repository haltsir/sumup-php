<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Product\Price;
use Sumup\Api\Repository\Collection;

class PriceFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var Price
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Price $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}
