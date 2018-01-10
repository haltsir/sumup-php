<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Traits\HydratorTrait;

class TransactionLink
{
    use HydratorTrait;

    /**
     * @var string
     */
    public $rel;

    /**
     * @var string
     */
    public $href;
}
