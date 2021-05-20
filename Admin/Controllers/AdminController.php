<?php

namespace Admin\Controllers;

use Admin\Models\AdminModel;
use Militer\mvcCore\Controller\aPageController;
use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Response\iResponse;

class AdminController extends aPageController
{
    protected AdminModel $Model;


    public function __construct(AdminModel $Model)
    {
        parent::__construct();
        $this->Model = $Model;
    }


}
