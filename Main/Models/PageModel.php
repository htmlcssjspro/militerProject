<?php

namespace Main\Models;

use Militer\mvcCore\Model\aPageModel;
use Militer\mvcCore\View\View;

class PageModel extends aPageModel
{
    public string $layout;
    public string $header;
    public string $footer;
    public string $aside;

    public string $mainContent;

    public array $layoutPopups;

    public array $includes;
    public array $popups;

    public string $mainCSS;
    public string $mainJS;

    public string $pageCSS;
    public string $pageJS;

    public string $title;
    public string $description;
    public string $h1;

    public array $headerNav;
    public array $asideNav;
    public array $footerNav;

    public array $sections;


    // public array $userData = [];
    public array $data = [];


    // public $guest = true;
    // public $user = false;

    // public $page = null;
    // public $main = null;

    // public $requestUri;
    // public $requestArray;

    private $pageId;


    public function __construct()
    {
        parent::__construct();
    }


    public function getPageId($controller, $action)
    {
        $sql = "SELECT `page_id` FROM `{$this->mainSitemapTable}` WHERE `controller`=? AND `action`=? LIMIT 1";
        $pdostmt = $this->PDO->prepare($sql);
        $pdostmt->execute([$controller, $action]);
        $pageId = $pdostmt->fetchColumn();
        return $pageId;
    }

    public function setPageId($controller, $action)
    {
        $pageId = $this->getPageId($controller, $action);
        $pageId ? $this->pageId = $pageId : $this->Response->notFound();
    }


    public function renderPage()
    {
        $this->setLayoutData();
        $this->setMainData();

        \ob_start();
        $Model = $this;
        require $this->layout;
        $page = \ob_get_clean();
        View::renderPage($page);
    }

    private function setLayoutData()
    {
        $layoutData = $this->getLayoutData();


        $this->layout = \MAIN_VIEWS . "/layouts/{$layoutData['layout']}.php";

        $this->header = \MAIN_VIEWS . "/components/{$layoutData['header']}.php";
        $this->footer = \MAIN_VIEWS . "/components/{$layoutData['footer']}.php";
        $this->aside  = \MAIN_VIEWS . "/components/{$layoutData['aside']}.php";

        $this->mainCSS = "/public/css/{$layoutData['css']}.css";
        $this->mainJS  = "/public/js/{$layoutData['js']}.js";

        foreach ($this->getSections() as $section) {
            $this->sections[$section['name']] = \MAIN_VIEWS . "/sections/{$section['file']}.php";
        }

        $this->headerNav = $this->getHeaderData();
        // $this->footerData = $this->getFooterData();
        // $this->asideData = $this->getAsideData();
    }

    private function getLayoutData()
    {
        $sql = "SELECT `layout`, `header`, `footer`, `aside`, `css`, `js` FROM `{$this->mainLayoutsTable}` WHERE `current`=1";
        $layoutData = $this->PDO->query($sql)->fetch();
        return $this->escapeOutput($layoutData);
    }

    private function getHeaderData()
    {
        $sql = "SELECT `label`, `page_url` FROM `{$this->mainSitemapTable}` WHERE `nav`=1 ORDER by `nav_order`";
        $headerNav = $this->PDO->query($sql)->fetchAll();
        return $this->escapeOutput($headerNav);
    }

    private function getFooterData()
    {
        $sql = "SELECT `label`, `page_url` FROM `{$this->mainSitemapTable}` WHERE `footer_nav`=1";
        $footerNav = $this->PDO->query($sql)->fetchAll();
        return $this->escapeOutput($footerNav);
    }

    private function getAsideData()
    {
        $sql = "SELECT `label`, `page_url` FROM `{$this->mainSitemapTable}` WHERE `aside_nav`=1";
        $asideNav = $this->PDO->query($sql)->fetchAll();
        return $this->escapeOutput($asideNav);
    }

    private function getSections()
    {
        $sql = "SELECT `name`, `file` FROM `{$this->mainSectionsTable}`";
        $sectionsData = $this->PDO->query($sql)->fetchAll();
        return $this->escapeOutput($sectionsData);
    }


    public function renderMain()
    {
        $this->setMainData();

        \ob_start();
        $Model = $this;
        require $this->mainContent;
        $main['content'] = \ob_get_clean();
        $main['title'] = $this->title;
        $main['description'] = $this->description;
        $main['h1'] = $this->h1;
        View::renderMain($main);
    }

    private function setMainData()
    {
        $mainData = $this->getMainData();
        $this->mainContent = \MAIN_VIEWS . "/pages/{$mainData['main_content']}.php";
        $this->pageCSS = $mainData['page_css'] ? "/public/css/{$mainData['page_css']}.css" : '';
        $this->pageJS  = $mainData['page_js'] ? "/public/js/{$mainData['page_js']}.js" : '';
        $this->title       = $mainData['title'];
        $this->description = $mainData['description'];
        $this->h1          = $mainData['h1'];

        // $this->mainContent = $this->config['main']['pages'][$this->pageId]['mainContent'];
        // $this->pageCSS[]   = $this->config['main']['pages'][$this->pageId]['pageCSS'];
        // $this->pageJS[]    = $this->config['main']['pages'][$this->pageId]['pageJS'];
    }

    private function getMainData()
    {
        // $sql = "SELECT `title`, `description`, `h1` FROM `{$this->mainSitemapTable}` WHERE `page_id`='{$this->pageId}'";
        $sql = "SELECT `main_content`, `title`, `description`, `h1`, `page_css`, `page_js`
        FROM `{$this->mainSitemapTable}` WHERE `page_id`='{$this->pageId}'";
        $mainData = $this->PDO->query($sql)->fetch();
        return $mainData;
    }




    public function test()
    {
        $sql = "SELECT `page_id`, `controller`, `action` FROM `{$this->mainSitemapTable}` WHERE `page_url`=?";
        $pdostmt = $this->PDO->prepare($sql);
        $pdostmt->execute([$this->requestUri]);
        $page = $pdostmt->fetch();
        !$page && $$this->Response->notFound();

        $pageId = $page['page_id'];
        $controller = $page['controller'];
        $action     = $page['action'] ?? 'index';
    }
}
