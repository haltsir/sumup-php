<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Traits\HydratorTrait;

class Emv
{
    use HydratorTrait;

    /**
     * @var string
     */
    public $tvr;

    /**
     * @var string
     */
    public $tsi;

    /**
     * @var string
     */
    public $cvr;

    /**
     * @var string
     */
    public $iad;

    /**
     * @var string
     */
    public $arc;

    /**
     * @var string
     */
    public $aid;

    /**
     * @var string
     */
    public $act;

    /**
     * @var string
     */
    public $acv;
}
