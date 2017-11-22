<?php

namespace Sumup\Api\Model\Merchant;

use Sumup\Api\Traits\HydratorTrait;

class Country
{
    use HydratorTrait;

    const MAP = [
            'isoCode' => ['path' => 'iso_code'],
            'enName' => ['path' => 'en_name'],
            'nativeName' => ['path' => 'native_name'],
        ];

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $isoCode;

    /**
     * @var string
     */
    public $enName;

    /**
     * @var string
     */
    public $nativeName;
}
