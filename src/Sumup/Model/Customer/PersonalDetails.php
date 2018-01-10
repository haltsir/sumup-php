<?php

namespace Sumup\Api\Model\Customer;

use Sumup\Api\Model\Merchant\Address;
use Sumup\Api\Traits\HydratorTrait;

class PersonalDetails
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = ['address' => ['path' => 'address', 'type' => Address::class]];

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var Address
     */
    public $address;
}