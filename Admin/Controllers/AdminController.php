<?php

namespace App\Controllers;

use App\Models\AdminModel;
use Core\Controller\aPageController;
use Core\DI\Container;
use Core\Http\Response\iResponse;

class AdminController extends aPageController
{
    private $adminConfig;

    public function __construct(AdminModel $Model)
    {
        parent::__construct();
        $this->Model = $Model;

        $this->adminConfig = $this->config['admin'];

        $this->Model->layout = $this->adminConfig['layout'];
        $this->Model->header = $this->adminConfig['header'];
        $this->Model->footer = $this->adminConfig['footer'];
        $this->Model->aside  = $this->adminConfig['aside'];

        $this->Model->layoutPopups = $this->adminConfig['layoutPopups'];

        $this->Model->mainCSS = $this->adminConfig['css'];
        $this->Model->mainJS  = $this->adminConfig['js'];

        $this->Model->headers[] = '';

        $this->Model->getAdminAsideData();
    }


    public function loginPage()
    {
        $this->pageId = 'admin_login_page';
        $this->Model->layout = $this->adminConfig['login'];
        // $this->model->pageCSS[] = '/public/css/militerslider.css';
        // $this->model->pageJS[]  = '/public/js/militerslider.js';
        $this->render();
    }

    public function index()
    {
        $this->adminCheck();
        $this->pageId = 'admin_home_page';
        $this->Model->mainContent = $this->adminConfig['pages']['home'];
        // $this->model->pageCSS[] = '/public/css/militerslider.css';
        // $this->model->pageJS[]  = '/public/js/militerslider.js';
        $this->render();
    }

    public function pages()
    {
        $this->adminCheck();
        $this->Model->mainContent = $this->adminConfig['pages']['pages'];
        $this->Model->popups[] = $this->adminConfig['popups']['newpage'];
        $this->Model->getPagesData();
        $this->pageId = 'admin_pages';
        $this->render();
    }

    public function usersList()
    {
        $this->adminCheck();
        $this->Model->mainContent = $this->adminConfig['pages']['userslist'];
        $this->Model->getUsersList();
        $this->Model->userDict = Container::get('userDict');
        $this->pageId = 'admin_userslist';
        $this->render();
    }

    public function preferences()
    {
        $this->adminCheck();
        $this->Model->mainContent = $this->adminConfig['pages']['preferences'];
        $this->Model->getLoginUrl();
        $this->pageId = 'admin_preferences';
        $this->render();
    }


    private function adminCheck()
    {
        if (!isset($_SESSION['admin'])) {
            $Response = Container::get(iResponse::class);
            $Response->notFound();
        }
    }



}
