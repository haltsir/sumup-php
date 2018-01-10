<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Transaction\TransactionHistory;
use Sumup\Api\Repository\Collection;

class TransactionHistoryFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var TransactionHistory
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(TransactionHistory $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}
