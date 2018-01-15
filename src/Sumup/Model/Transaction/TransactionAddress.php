<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Traits\HydratorTrait;

class TransactionAddress
{
    use HydratorTrait;
    /**
     * @var string
     */
    public $addressLine1;

    /**
     * @var string
     */
    public $addressLine2;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $countryEnName;

    /**
     * @var string
     */
    public $countryNativeName;

    /**
     * @var int
     */
    public $regionId;

    /**
     * @var string
     */
    public $regionName;

    /**
     * @var string
     */
    public $postCode;

    /**
     * @var string
     */
    public $landline;

    /**
     * @var string
     */
    public $line1;

    /**
     * @var string
     */
    public $line2;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @var string
     */
    public $state;
}
