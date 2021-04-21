<?php

namespace Admin\Models;

use Militer\mvcCore\Model\aApiModel;

class AdminApiModel extends aApiModel
{
    public function __construct()
    {
        parent::__construct();
    }


    public function login($login)
    {
        $sql = "SELECT `password` FROM `{$this->adminTable}` WHERE `login`=?";
        $pdostmt = $this->PDO->prepare($sql);
        $pdostmt->execute([$login]);
        return $pdostmt->fetch();
    }

    public function updateLoginUrl($loginUrl)
    {
        $sql = "UPDATE {$this->sitemapTable} SET `page_url`=? WHERE `page_id`='admin_login_page'";
        $pdostmt = $this->PDO->prepare($sql);
        return $pdostmt->execute([$loginUrl]);
    }
    public function updateLogin($login)
    {
        $sql = "UPDATE {$this->adminTable} SET `login`=? WHERE `login`=?";
        $pdostmt = $this->PDO->prepare($sql);
        return  $pdostmt->execute([$login, $_SESSION['admin']]);
    }
    public function updatePassword($password)
    {
        $sql = "UPDATE {$this->adminTable} SET `password`=? WHERE `login`=?";
        $pdostmt = $this->PDO->prepare($sql);
        return $pdostmt->execute([$password, $_SESSION['admin']]);
    }

}
