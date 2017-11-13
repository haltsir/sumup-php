<?php

namespace Sumup\Api\Model\Merchant;

class Account
{
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
