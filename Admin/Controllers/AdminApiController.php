<?php

namespace Admin\Controllers;

use Admin\Models\AdminApiModel;
use Militer\mvcCore\Controller\aAdminApiController;
use Militer\mvcCore\Model\iAdminApiModel;

class AdminApiController extends aAdminApiController
{
    public iAdminApiModel $Model;


    public function __construct(AdminApiModel $Model)
    {
        $this->Model = $Model;
        parent::__construct();
    }


    public function method()
    {
        // Code
    }


    public function test()
    {
        $this->Model->test();
    }
}
