<?php

namespace Sumup\Api\Model\Merchant;
use Sumup\Api\Model\Mobile\Settings;
use Sumup\Api\Model\Operator\Operator;
use Sumup\Api\Model\Operator\Permission;
use Sumup\Api\Model\Product\Shelf;
use Sumup\Api\Traits\HydratorTrait;

class Me
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = ['account' => ['path' => 'account', 'type' => Account::class],
                                'personalProfile' => ['path' => 'personal_profile', 'type' => Profile::class],
                                'merchantProfile' => ['path' => 'merchant_profile', 'type' => Merchant::class],
                                'operators' => ['path' => 'operators', 'type' => 'array', 'subtype' => Operator::class],
                                'appSettings' => ['path' => 'app_settings', 'type' => Settings::class],
                                'signupFlows' => ['path' => 'signup_flows', 'type' => 'array',
                                                  'subtype' => SignupFlow::class],
                                'shelves' => ['path' => 'shelves', 'type' => 'array', 'subtype' => Shelf::class],];


    /**
     * @var Account
     */
    public $account;

    /**
     * @var Profile
     */
    public $personalProfile;

    /**
     * @var Merchant
     */
    public $merchantProfile;

    /**
     * @var array
     */
    public $operators;

    /**
     * @var Settings
     */
    public $appSettings;

    /**
     * @var array
     */
    public $signupFlows;

    /**
     * @var array
     */
    public $shelves;

    /**
     * @var Permission
     */
    public $permissions;
}