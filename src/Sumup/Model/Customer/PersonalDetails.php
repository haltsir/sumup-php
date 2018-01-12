<?php

namespace Sumup\Api\Model\Customer;

use Sumup\Api\Model\Transaction\TransactionAddress;
use Sumup\Api\Traits\HydratorTrait;

class PersonalDetails
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = ['address' => ['path' => 'address', 'type' => TransactionAddress::class]];

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var TransactionAddress
     */
    public $address;
}