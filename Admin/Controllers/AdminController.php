<?php

namespace Admin\Controllers;

use Admin\Models\AdminModel;
use Militer\mvcCore\Controller\aPageController;

class AdminController extends aPageController
{
    protected AdminModel $Model;


    public function __construct(AdminModel $Model)
    {
        $this->Model = $Model;
        parent::__construct();
    }


    public function method()
    {
        // Code
    }
}
