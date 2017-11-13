<?php

namespace Sumup\Api\Model\Employee;

use Sumup\Api\Model\Mobile\Settings;

class Employee
{
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

    /**
     * @var Permission
     */
    public $permissions;

    /**
     * @var Settings
     */
    public $appSettings;
}
