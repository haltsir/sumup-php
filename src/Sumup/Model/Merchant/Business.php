<?php

namespace Sumup\Api\Model\Merchant;

use Sumup\Api\Traits\HydratorTrait;

class Business
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = [
        'businessName' => ['path' => 'business_name'],
        'address' => [
            'path' => 'address',
            'type' => Address::class
        ]
    ];

    /**
     * @var string
     */
    public $businessName;

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
