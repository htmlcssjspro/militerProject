<?php

namespace Admin\Models;

use Militer\mvcCore\Model\aAdminApiModel;

class AdminApiModel extends aAdminApiModel
{


    public function __construct()
    {
        parent::__construct();
    }


    public function method()
    {
        $this->adminCheck();
        // Code
    }
}
