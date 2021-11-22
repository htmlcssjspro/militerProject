<?php

namespace Main\Controllers;

use Militer\mvcCore\Controller\aMainApiController;
use Militer\mvcCore\Model\interfaces\iMainApiModel;

class MainApiController extends aMainApiController
{
    public iMainApiModel $Model;


    public function __construct(iMainApiModel $Model)
    {
        $this->Model = $Model;
        parent::__construct();
    }


    public function method()
    {
        // Code
    }
}
