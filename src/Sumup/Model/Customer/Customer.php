<?php

namespace Sumup\Api\Model\Customer;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Customer
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY = ['personalDetails' => ['path' => 'personal_details', 'type' => PersonalDetails::class]];

    /**
     * @var string
     */
    public $customerId;

    /**
     * @var PersonalDetails
     */
    public $personalDetails;
}