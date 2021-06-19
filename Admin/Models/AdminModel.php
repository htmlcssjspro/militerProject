<?php

namespace Admin\Models;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Model\aAdminModel;

class AdminModel extends aAdminModel
{


    public function __construct()
    {
        parent::__construct();
    }


    public function init(string $requestUri): void
    {
        parent::init($requestUri);
    }


    public function test()
    {
        $this->adminCheck();
        // Code
    }
}
