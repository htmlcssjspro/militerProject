<?php

namespace Admin\Controllers;

use Admin\Models\AdminApiModel;
use Militer\mvcCore\Controller\aAdminApiController;

class AdminApiController extends aAdminApiController
{
    public AdminApiModel $Model;


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
        $this->User->test();
    }
}
