<?php

namespace controllers;

use framework\endpoints\BaseEndpoint;
use services\UsersService;

class UsersController extends BaseEndpoint
{
    private UsersService $_usersService;
    public function __construct()
    {
        parent::__construct();
        $this->_usersService = new UsersService($this->systemPath);
    }
    public function defaultParams()
    {
        return [
            'method' => "",
        ];
    }
    public function build()
    {
        if ($this->getParam('method') == "generateUser") {
            return $this->_usersService->generateUser();
        }
    }
}
