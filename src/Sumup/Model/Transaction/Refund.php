<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Refund
{
    use HydratorTrait, SerializerTrait;

    /**
     * @var float
     */
    public $amount;
}