<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Transaction\Refund;
use Sumup\Api\Repository\Collection;

class RefundFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var Refund
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Refund $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}
