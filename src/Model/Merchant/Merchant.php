<?php

namespace Sumup\Api\Model\Merchant;

class Merchant
{
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
