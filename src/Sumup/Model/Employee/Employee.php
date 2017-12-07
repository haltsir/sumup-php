<?php

namespace Sumup\Api\Model\Employee;

use Sumup\Api\Traits\HydratorTrait;
use Sumup\Api\Traits\SerializerTrait;

class Employee
{
    use HydratorTrait, SerializerTrait;

    const MAP_JSON_TO_ENTITY
        = [
            'createdAt' => ['path' => 'created_at']
        ];

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
