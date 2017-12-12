<?php

namespace Sumup\Api\Model\Mobile;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Settings
{
    use HydratorTrait, SerializerTrait;

    /**
     * @var string
     */
    public $checkoutPreference;

    /**
     * @var bool
     */
    public $activateAirHint;

    /**
     * @var bool
     */
    public $soundEnabled;

    /**
     * @var bool
     */
    public $tipsEnabled;

    /**
     * @var string
     */
    public $fontName;

    /**
     * @var bool
     */
    public $includeVat;

    /**
     * @var string
     */
    public $maxAmountNoSignature;

    /**
     * @var bool
     */
    public $manualEntryTutorial;

    /**
     * @var bool
     */
    public $taxEnabled;

    /**
     * @var string
     */
    public $mobilePayment;

    /**
     * @var string
     */
    public $readerPayment;

    /**
     * @var string
     */
    public $cashPayment;

    /**
     * @var string
     */
    public $advancedMode;

    /**
     * @var int
     */
    public $expectedMaxTransactionAmount;

    /**
     * @var string
     */
    public $bitcoinPayment;

    /**
     * @var string
     */
    public $manualEntry;

    /**
     * @var bool
     */
    public $terminalModeTutorial;

    /**
     * @var string
     */
    public $tipping;

    /**
     * @var array
     */
    public $tipRates;

    /**
     * @var string
     */
    public $barcodeScanner;

    /**
     * @var string
     */
    public $referral;
}
