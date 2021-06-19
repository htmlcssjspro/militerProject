<?php

namespace Main\Controllers;

use Main\Models\MainApiModel;
use Militer\mvcCore\Controller\aMainApiController;

class MainApiController extends aMainApiController
{
    public MainApiModel $Model;


    public function __construct(MainApiModel $Model)
    {
        $this->Model = $Model;
        parent::__construct();
    }


    public function method()
    {
        // Code
    }
}
