<?php

namespace Admin\Models;

use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Model\aPageModel;

class AdminModel extends aPageModel
{


    public function __construct()
    {
        parent::__construct();
        $this->sitemapTable  = self::ADMIN_SITEMAP_TABLE;
        $this->layoutsTable  = self::ADMIN_LAYOUTS_TABLE;
        $this->sectionsTable = self::ADMIN_SECTIONS_TABLE;
        $this->views = \ADMIN_VIEWS;
    }


    public function init(string $requestUri)
    {
        $this->adminCheck();
        parent::init($requestUri);
    }


    protected function getAdminAsideData()
    {
        $table = $this->sitemapTable;
        // $table = self::ADMIN_SITEMAP_TABLE;
        $sql = "SELECT `label`, `page_uri` FROM `{$table}` WHERE `admin_aside`=1";
        return self::$PDO::queryFetchAll($sql);
    }

    protected function getPagesData()
    {
        $table = self::MAIN_SITEMAP_TABLE;
        $sql = "SELECT `label`, `page_uri`, `title`, `description`, `h1` FROM `{$table}` WHERE `admin`=1";
        return self::$PDO::queryFetchAll($sql);
    }

    protected function getUsersList()
    {
        $table = self::USERS_TABLE;
        $sql = "SELECT `user_uuid`, `username`, `name`, `email`, `status`, `phone`, `last_visit`, `register_date` FROM `{$table}`";
        return self::$PDO::queryFetchAll($sql);
    }


    private function adminCheck()
    {
        !isset($_SESSION['admin_uuid']) && $this->renderLoginPage();
    }
    private function renderLoginPage()
    {
        \ob_start();
        $Model = $this;
        require "{$this->views}/layouts/login.php";
        $page = \ob_get_clean();
        $this->Response->sendPage($page);
    }
}
