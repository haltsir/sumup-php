<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Traits\HydratorTrait;

class Acquirer
{
    use HydratorTrait;

    /**
     * @var string
     */
    public $tid;

    /**
     * @var string
     */
    public $mid;

    /**
     * @var string
     */
    public $authorizationCode;

    /**
     * @var string
     */
    public $mandateReference;

    /**
     * @var string
     */
    public $returnCode;

    /**
     * @var string
     */
    public $localTime;
}