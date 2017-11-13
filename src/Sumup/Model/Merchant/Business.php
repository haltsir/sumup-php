<?php

namespace Sumup\Api\Model\Merchant;

class Business
{
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
     * @var Address
     */
    public $address;
}
