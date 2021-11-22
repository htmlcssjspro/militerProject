<?php

namespace Admin\Controllers;

use Militer\mvcCore\Controller\aAdminApiController;
use Militer\mvcCore\Model\interfaces\iAdminApiModel;

class AdminApiController extends aAdminApiController
{
    public iAdminApiModel $Model;


    public function __construct(iAdminApiModel $Model)
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
