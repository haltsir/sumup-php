<?php

namespace Sumup\Api\Model\Factory;


use Sumup\Api\Model\Employee\Employee;
use Sumup\Api\Repository\Collection;

class SubaccountFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var Employee
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Employee $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }

}