<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Checkout\Checkout;
use Sumup\Api\Repository\Collection;

class CheckoutFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var Checkout
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Checkout $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}
