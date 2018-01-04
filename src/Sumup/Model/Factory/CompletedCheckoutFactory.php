<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Checkout\CompletedCheckout;
use Sumup\Api\Repository\Collection;

class CompletedCheckoutFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var CompletedCheckout
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(CompletedCheckout $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}
