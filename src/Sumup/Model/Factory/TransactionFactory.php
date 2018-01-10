<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Transaction\Transaction;
use Sumup\Api\Repository\Collection;

class TransactionFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var Transaction
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Transaction $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}
