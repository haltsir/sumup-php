<?php

namespace Sumup\Api\Model\Merchant;

use Sumup\Api\Traits\HydratorTrait;

class Address
{
    use HydratorTrait;

    const MAP = [
            'addressLine1' => ['path' => 'address_line1'],
            'addressLine2' => ['path' => 'address_line2'],
            'regionId' => ['path' => 'region_id'],
            'regionCode' => ['path' => 'region_code'],
            'postCode' => ['path' => 'post_code'],
            'landline' => ['path' => 'landline'],
            'stateId' => ['path' => 'state_id'],
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
     * @var Timeoffset
     */
    public $timeoffsetDetails;

    /**
     * @var string
     */
    public $stateId;
}
