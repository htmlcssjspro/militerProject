<?php

namespace App\Controllers;

use Main\Models\MainModel;
use Militer\mvcCore\Controller\aPageController;
use Militer\mvcCore\DI\Container;

class MainController extends aPageController
{

    public function __construct(MainModel $Model)
    {
        parent::__construct();
        $this->Model = $Model;

        if ($this->User->userUuid === 'guest') {
            $this->Model->userData['status'] = 'guest';
            $this->Model->guest = true;
        } else {
            $userData = $this->Model->userData = $this->User->getUserData();
            if ($userData['status'] === 'user') {
                $this->Model->user = true;
            }
        }

        $mainConfig = $this->config['main'];

        $this->Model->layout = $mainConfig['layout'];
        $this->Model->header = $mainConfig['header'];
        $this->Model->footer = $mainConfig['footer'];
        $this->Model->aside  = $mainConfig['aside'];
        $this->Model->access = $this->config['sections']['access'];
        $this->Model->slider = $this->config['sections']['slider'];

        $this->Model->mainCSS = $mainConfig['css'];
        $this->Model->mainJS  = $mainConfig['js'];

        $this->Model->code = 200;
        $this->Model->headers[] = '';
    }


    public function index()
    {
        $this->pageId = 'home';
        $this->Model->mainContent = $this->config['main']['pages']['home'];
        // $this->model->pageCSS[] = '/public/css/militerslider.css';
        // $this->model->pageJS[]  = '/public/js/militerslider.js';
        $this->render();
    }

    public function page()
    {
        $Request = Container::get(Request::class);
        $pageUri = $Request->getRequestUri();
        $pageID = $this->Model->pageID = $this->Model->getPageID($pageUri);
        $this->pageId = $pageID;
        $this->Model->mainContent = $this->config['main']['pages'][$pageID]['mainContent'];
        $this->model->pageCSS[]   = $this->config['main']['pages'][$pageID]['pageCSS'];
        $this->model->pageJS[]    = $this->config['main']['pages'][$pageID]['pageJS'];
        $this->render();
    }

    public function page1()
    {
        $this->pageId = 'page1';
        $this->Model->mainContent = $this->config['main']['pages']['page1'];
        // $this->model->pageCSS[] = '/public/css/militerslider.css';
        // $this->model->pageJS[]  = '/public/js/militerslider.js';
        $this->render();
    }
    public function page2()
    {
        $this->pageId = 'page2';
        $this->Model->mainContent = $this->config['main']['pages']['page2'];
        // $this->model->pageCSS[] = '/public/css/militerslider.css';
        // $this->model->pageJS[]  = '/public/js/militerslider.js';
        $this->render();
    }
    public function page3()
    {
        $this->pageId = 'page3';
        $this->Model->mainContent = $this->config['main']['pages']['page3'];
        // $this->model->pageCSS[] = '/public/css/militerslider.css';
        // $this->model->pageJS[]  = '/public/js/militerslider.js';
        $this->render();
    }
    public function page4()
    {
        $this->pageId = 'page4';
        $this->Model->mainContent = $this->config['main']['pages']['page4'];
        // $this->model->pageCSS[] = '/public/css/militerslider.css';
        // $this->model->pageJS[]  = '/public/js/militerslider.js';
        $this->render();
    }
    public function page5()
    {
        $this->pageId = 'page5';
        $this->Model->mainContent = $this->config['main']['pages']['page5'];
        // $this->model->pageCSS[] = '/public/css/militerslider.css';
        // $this->model->pageJS[]  = '/public/js/militerslider.js';
        $this->render();
    }

    public function user()
    {
        $this->pageId = 'user';
        $userConfig = $this->config['user'];
        if ($this->Model->guest) {
            $this->Model->mainContent = $userConfig['content']['guest'];
        } else {
            $this->Model->mainContent = $userConfig['content']['user'];
        }
        // $this->model->pageCSS[] = '/public/css/militerslider.css';
        // $this->model->pageJS[]  = '/public/js/militerslider.js';
        $this->render();
    }

}
