<?php

namespace Admin\Controllers;

use Admin\Models\AdminApiModel;
use Militer\mvcCore\Controller\aApiController;

class AdminApiController extends aApiController
{
    public AdminApiModel $Model;


    public function __construct(AdminApiModel $Model)
    {
        parent::__construct();
        $this->Model = $Model;
    }


    public function index(array $routerData)
    {
        \extract($routerData);
        // $this->methodVerify($method);
        \method_exists($this, $action)
            ? $this->$action()
            : $this->Response->badRequestMessage();
    }


    public function login()
    {
        $this->csrfVerify(function ($adminLoginData) {
            $this->Model->login($adminLoginData);
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
