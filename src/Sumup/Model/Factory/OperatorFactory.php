<?php

namespace Sumup\Api\Model\Factory;


use Sumup\Api\Model\Operator\Operator;
use Sumup\Api\Repository\Collection;

class OperatorFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var Operator
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Operator $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }

}