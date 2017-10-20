<?php

namespace Sumup\Api\Model\Merchant;

class VatRate
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $rate;

    /**
     * @var int
     */
    public $ordering;

    /**
     * @var string
     */
    public $country;
}
