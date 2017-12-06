<?php

namespace Sumup\Api\Model\Merchant;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Business
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY = [
        'name' => ['path' => 'business_name'],
        'address' => [
            'path' => 'address',
            'type' => Address::class
        ]
    ];

    const MAP_ENTITY_TO_JSON = [
        'name' => ['path' => 'business_name'],
        'address' => [
            'path' => 'address',
            'type' => Address::class
        ]
    ];

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $companyRegistrationNumber;

    /**
     * @var string
     */
    public $vatId;

    /**
     * @var string
     */
    public $website;

    /**
     * @var string
     */
    public $email;

    /**
     * @var Address
     */
    public $address;
}
