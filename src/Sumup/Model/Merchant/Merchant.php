<?php

namespace Sumup\Api\Model\Merchant;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Merchant
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY = [
        'merchantCode' => ['path' => 'merchant_code'],
        'companyName' => ['path' => 'company_name'],
        'legalType' => [
            'path' => 'legal_type',
            'type' => LegalType::class
        ],
        'merchantCategoryCode' => ['path' => 'merchant_category_code'],
        'mobilePhone' => ['path' => 'mobile_phone'],
        'companyRegistrationNumber' => ['path' => 'company_registration_number'],
        'vatId' => ['path' => 'vat_id'],
        'permanentCertificateAccessCode' => ['path' => 'permanent_certificate_access_code'],
        'natureAndPurpose' => ['path' => 'nature_and_purpose'],
        'address' => [
            'path' => 'address',
            'type' => Address::class
        ],
        'businessOwners' => [
            'path' => 'business_owners',
            'type' => 'array',
            'subtype' => Business::class
        ],
        'doingBusinessAs' => [
            'path' => 'doing_business_as',
            'type' => Business::class
        ]
    ];

    const MAP_ENTITY_TO_JSON = [
        'legalType.id' => ['path' => 'legal_type_id']
    ];

    /**
     * @var string
     */
    public $merchantCode;

    /**
     * @var string
     */
    public $companyName;

    /**
     * @var string
     */
    public $website;

    /**
     * @var LegalType
     */
    public $legalType;

    /**
     * @var string
     */
    public $merchantCategoryCode;

    /**
     * @var string
     */
    public $mobilePhone;

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
    public $permanentCertificateAccessCode;

    /**
     * @var string
     */
    public $natureAndPurpose;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var array
     */
    public $businessOwners;

    /**
     * @var Business
     */
    public $doingBusinessAs;

    /**
     * @var Settings
     */
    public $settings;

    /**
     * @var VatRate
     */
    public $vatRates;

    /**
     * @var array
     */
    public $shelves;

    /**
     * @var array
     */
    public $bankAccounts;

    /**
     * @var bool
     */
    public $extdev;

    /**
     * @var bool
     */
    public $payoutZoneMigrated;

    /**
     * @var string
     */
    public $countryCode;
}
