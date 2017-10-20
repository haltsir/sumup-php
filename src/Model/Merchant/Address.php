<?php

namespace Sumup\Api\Model\Merchant;

class Address
{
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
