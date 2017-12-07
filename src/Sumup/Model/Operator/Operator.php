<?php

namespace Sumup\Api\Model\Operator;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Operator
{
    use HydratorTrait, SerializerTrait;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    public $createdAt;

}
