<?php

namespace Sumup\Api\Model\Merchant;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Profile
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY = [
            'firstName' => ['path' => 'personal_profile.first_name'],
            'lastName' => ['path' => 'personal_profile.last_name'],
            'dateOfBirth' => ['path' => 'personal_profile.date_of_birth'],
            'mobilePhone' => ['path' => 'personal_profile.mobile_phone'],
            'nationalId' => ['path' => 'personal_profile.national_id'],
            'address' => [
                'path' => 'personal_profile.address',
                'type' => Address::class
            ],
        ];

    const MAP_ENTITY_TO_JSON = [
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
