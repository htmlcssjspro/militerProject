<?php

namespace Admin\Models;

use Militer\mvcCore\Model\aApiModel;

class AdminApiModel extends aApiModel
{


    public function __construct()
    {
        parent::__construct();
    }


    public function login(array $adminLoginData)
    {
        \extract($adminLoginData);
        $passwordHash = $this->getLoginData($login);
        \password_verify($password, $passwordHash) && $_SESSION['admin'] = $login;
    }
    private function getLoginData(string $login)
    {
        $table = self::ADMIN_TABLE;
        $sql = "SELECT `password` FROM `{$table}` WHERE `login`=?";
        return self::$PDO::prepFetchColumn($sql, $login);
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
