<?php

namespace App\Controllers;

use App\Models\AdminApiModel;
use Core\Controller\aApiController;

class AdminApiController extends aApiController
{

    public function __construct(AdminApiModel $Model)
    {
        parent::__construct();
        $this->Model = $Model;
    }


    public function login()
    {
        $this->csrfVerify(function ($adminLoginData) {
            $passwordHash = $this->Model->login($adminLoginData['login'])['password'];
            \password_verify($adminLoginData['password'], $passwordHash) && $_SESSION['admin'] = $adminLoginData['login'];
        });
    }

    public function preferences()
    {
        $this->csrfVerify(function ($preferencesData) {
            $this->adminCheck();
            $result = $this->Model->login(['login' => $_SESSION['admin'], 'password' => $preferencesData['password']]);
            !$result && $this->Response->sendJson(['message' => 'Неверный пароль администратора']);

            $result = $this->Model->updateLoginUrl($preferencesData['login-url']);
            !$result && $this->Response->sendJson(['message' => 'Ошибка обновления страницы ввода пароля администратора']);

            if ($preferencesData['new-login']) {
                $result = $this->Model->updateLogin($preferencesData['new-login']);
                !$result && $this->Response->sendJson(['message' => 'Ошибка обновления логина администратора']);
                $result && $_SESSION['admin'] = $preferencesData['new-login'];
            }

            if ($preferencesData['new-password']) {
                $passwordHash = \password_hash($preferencesData['new-password'], \PASSWORD_DEFAULT);
                $result = $this->Model->updatePassword($passwordHash);
                !$result && $this->Response->sendJson(['message' => 'Ошибка обновления пароля администратора']);
            }
            $this->Response->sendJson(['message' => 'Настройки успешно обновлены']);
        });
    }


    private function adminCheck()
    {
        if (!isset($_SESSION['admin'])) {
            $this->Response->notFound();
        }
    }
}
