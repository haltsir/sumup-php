<?php

namespace Sumup\Api\Model\Merchant;

use Sumup\Api\Traits\HydratorTrait;

class Profile
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = [
            'firstName' => ['path' => 'first_name'],
            'lastName' => ['path' => 'last_name'],
            'dateOfBirth' => ['path' => 'date_of_birth'],
            'mobilePhone' => ['path' => 'mobile_phone'],
            'nationalId' => ['path' => 'national_id'],
            'address' => [
                'path' => 'address',
                'type' => Address::class
            ],
        ];

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $dateOfBirth;

    /**
     * @var string
     */
    public $mobilePhone;

    /**
     * @var string
     */
    public $nationalId;

    /**
     * @var Address
     */
    public $address;
}
