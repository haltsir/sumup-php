<?php

namespace Sumup\Api\Model\Payout;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Settings
{
    use HydratorTrait, SerializerTrait;

    /**
     * @var bool
     */
    public $taxEnabled;

    /**
     * @var string
     */
    public $payoutType;

    /**
     * @var string
     */
    public $payoutPeriod;

    /**
     * @var bool
     */
    public $payoutOnDemandAvailable;

    /**
     * @var bool
     */
    public $payoutOnDemand;

    /**
     * @var bool
     */
    public $printersEnabled;

    /**
     * @var bool
     */
    public $payoutInstrument;

    /**
     * @var bool
     */
    public $motoPayment;

    /**
     * @var string
     */
    public $stoneMerchantCode;

    /**
     * @var string
     */
    public $adyenMerchantCode;

    /**
     * @var string
     */
    public $adyenUser;

    /**
     * @var string
     */
    public $adyenPassword;

    /**
     * @var string
     */
    public $adyenCompany;

    /**
     * @var bool
     */
    public $dailyPayoutEmail;

    /**
     * @var bool
     */
    public $monthlyPayoutEmail;
}
