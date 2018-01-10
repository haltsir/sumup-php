<?php

namespace Sumup\Api\Model\Merchant;

use Sumup\Api\Traits\HydratorTrait;

class Address
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = [
        'countryNameEnglish' => ['path' => 'country_en_name'],
        'countryNameNative' => ['path' => 'country_native_name'],
        'countryDetails' => [
            'path' => 'country_details',
            'type' => Country::class
        ],
        'timeoffsetDetails' => [
            'path' => 'timeoffset_details',
            'type' => Timeoffset::class
        ]
    ];

    /**
     * @var string
     */
    public $addressLine1;

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
    public $regionId;

    /**
     * @var string
     */
    public $regionCode;

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
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $company;

    /**
     * @var Country
     */
    public $countryDetails;

    /**
     * @var string
     */
    public $countryNameEnglish;

    /**
     * @var string
     */
    public $countryNameNative;

    /**
     * @var Timeoffset
     */
    public $timeoffsetDetails;

    /**
     * @var string
     */
    public $stateId;
}
