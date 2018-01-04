<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Transaction\TransactionItem;
use Sumup\Api\Repository\Collection;

class TransactionItemFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var TransactionItem
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(TransactionItem $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}
