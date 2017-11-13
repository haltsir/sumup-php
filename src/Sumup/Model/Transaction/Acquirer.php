<?php

namespace Sumup\Api\Model\Transaction;

class Acquirer
{
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