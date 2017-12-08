<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Merchant\BankAccount;
use Sumup\Api\Repository\Collection;

class BankAccountFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var BankAccount
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(BankAccount $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}
