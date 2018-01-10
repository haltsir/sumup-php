<?php
namespace Sumup\Api\Model\Factory;

use Sumup\Api\Model\Customer\Customer;
use Sumup\Api\Repository\Collection;

class CustomerFactory extends FactoryAbstract implements FactoryInterface
{
    /**
     * @var Customer
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Customer $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}