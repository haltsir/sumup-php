<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Model\Merchant\LegalType;
use Sumup\Api\Model\Merchant\Settings;

class ReceiptMerchant
{
    /**
     * @var string
     */
    public $merchantCode;

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
     * @var TransactionAddress
     */
    public $address;

    /**
     * @var Settings
     */
    public $settings;

    /**
     * @var LegalType
     */
    public $legalType;

    /**
     * @var string
     */
    public $nationalId;

    /**
     * @var string
     */
    public $locale;
}
