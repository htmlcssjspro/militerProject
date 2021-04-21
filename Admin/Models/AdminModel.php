<?php

namespace Admin\Models;

use Militer\mvcCore\Model\aPageModel;

class AdminModel extends aPageModel
{
    public array $pagesData = [];
    public string $loginUrl;


    public function __construct()
    {
        parent::__construct();
    }


    public function getAdminAsideData()
    {
        $sql = "SELECT `label`, `page_url` FROM {$this->sitemapTable} WHERE `admin_aside`=1";
        $this->adminAsideData = $this->PDO->query($sql)->fetchAll();
    }

    public function getPagesData()
    {
        $sql = "SELECT `label`, `page_url`, `title`, `description`, `h1` FROM `{$this->sitemapTable}` WHERE `admin`=1";
        $this->pagesData = $this->PDO->query($sql)->fetchAll();
    }

    public function getUsersList()
    {
        $sql = "SELECT `user_uuid`, `username`, `name`, `email`, `status`, `phone`, `last_visit`, `register_date` FROM {$this->usersTable}";
        $this->usersList = $this->PDO->query($sql)->fetchAll();
    }

    public function getLoginUrl()
    {
        $sql = "SELECT `page_url` FROM {$this->sitemapTable} WHERE `page_id`='admin_login_page'";
        $this->loginUrl = $this->PDO->query($sql)->fetchColumn();
    }


}
