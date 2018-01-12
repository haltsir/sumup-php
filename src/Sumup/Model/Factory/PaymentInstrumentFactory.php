<?php

namespace Sumup\Api\Model\Factory;


use Sumup\Api\Model\Customer\PaymentInstrument;
use Sumup\Api\Repository\Collection;

class PaymentInstrumentFactory extends FactoryAbstract implements FactoryInterface {

    /**
     * @var PaymentInstrument
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(PaymentInstrument $model, Collection $collection)
    {
        $this->model = $model;
        $this->collection = $collection;
    }
}