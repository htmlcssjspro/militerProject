<?php

namespace Main\Controllers;

use Main\Models\PageModel;
use Militer\mvcCore\Controller\aPageController;

class MainController extends aPageController
{
    protected PageModel $Model;


    public function __construct(PageModel $Model)
    {
        parent::__construct();
        $this->Model = $Model;
    }

}
