<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Product\Shelf;
use Sumup\Api\Repository\Collection;

class ShelfFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var Shelf
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Shelf $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}
