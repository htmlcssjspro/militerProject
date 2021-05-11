<?php

namespace Main\Controllers;

use Main\Models\MainModel;
use Main\Models\PageModel;
use Militer\mvcCore\Controller\aPageController;
use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Model\iModel;

class MainController extends aPageController
{
    private $Model;


    public function __construct(PageModel $Model)
    {
        parent::__construct();
        $this->Model = $Model;

        // if ($this->User->userUuid === 'guest') {
        //     $this->Model->userData['status'] = 'guest';
        //     $this->Model->guest = true;
        // } else {
        //     $userData = $this->Model->userData = $this->User->getUserData();
        //     if ($userData['status'] === 'user') {
        //         $this->Model->user = true;
        //     }
        // }

    }


    public function index(array $routerData)
    {
        $method       = $routerData['method'];
        $requestUri   = $routerData['requestUri'];
        $controller   = $routerData['controller'];
        $action       = $routerData['action'];
        $query        = $routerData['query'];
        // $requestArray = $routerData['requestArray'];

        $this->Model->setPageId($controller, $action);
        // $this->Model->requestArray = $routerData['requestArray'];
        // $this->Model->requestUri = $routerData['requestUri'];

        $method === 'get'  && $this->Model->renderPage();
        $method === 'post' && $this->Model->renderMain();
    }

    public function page()
    {
    }

    // public function page()
    // {
    //     $Request = Container::get(Request::class);
    //     $pageUri = $Request->getRequestUri();
    //     $pageID = $this->Model->pageID = $this->Model->getPageID($pageUri);
    //     $this->pageId = $pageID;
    //     $this->Model->mainContent = $this->config['main']['pages'][$pageID]['mainContent'];
    //     $this->model->pageCSS[]   = $this->config['main']['pages'][$pageID]['pageCSS'];
    //     $this->model->pageJS[]    = $this->config['main']['pages'][$pageID]['pageJS'];
    //     $this->render();
    // }


    // public function user()
    // {
    //     $this->pageId = 'user';
    //     $userConfig = $this->config['user'];
    //     if ($this->Model->guest) {
    //         $this->Model->mainContent = $userConfig['content']['guest'];
    //     } else {
    //         $this->Model->mainContent = $userConfig['content']['user'];
    //     }
    //     // $this->model->pageCSS[] = '/public/css/militerslider.css';
    //     // $this->model->pageJS[]  = '/public/js/militerslider.js';
    //     // $this->render();
    // }

}
