<?php

namespace Sumup\Api\Model\Merchant;

use Sumup\Api\Traits\HydratorTrait;

class LegalType
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = [
        'soleTrader' => ['path' => 'sole_trader']
    ];

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $description;

    /**
     * @var bool
     */
    public $soleTrader;
}
