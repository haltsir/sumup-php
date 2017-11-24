<?php

namespace Sumup\Api\Model\Merchant;

use Sumup\Api\Traits\HydratorTrait;

class Timeoffset
{
    use HydratorTrait;

    const MAP_JSON_TO_ENTITY = [
            'postCode' => ['path' => 'post_code'],
        ];


    /**
     * @var string
     */
    public $postCode;

    /**
     * @var int
     */
    public $offset;

    /**
     * @var bool
     */
    public $dst;
}
