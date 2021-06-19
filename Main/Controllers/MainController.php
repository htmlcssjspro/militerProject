<?php

namespace Main\Controllers;

use Main\Models\MainModel;
use Militer\mvcCore\Controller\aPageController;

class MainController extends aPageController
{
    protected MainModel $Model;


    public function __construct(MainModel $Model)
    {
        $this->Model = $Model;
        parent::__construct();
    }


    public function method()
    {
        // Code
    }
}
