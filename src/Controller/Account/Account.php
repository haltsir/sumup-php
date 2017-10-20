<?php

namespace Sumup\Api\Controller\Account;

use Sumup\Api\Controller\Controller;
use Sumup\Api\Service\Account\AccountService;

class Account extends Controller
{
    /**
     * @var AccountService
     */
    protected $accountService;

    public function __construct()
    {
        $this->accountService = new AccountService();
    }

    public function get()
    {
        $response = $this->accountService->get();
        var_dump(__METHOD__, $response);
    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
