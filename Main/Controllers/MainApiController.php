<?php

namespace Main\Controllers;

use Main\Models\MainApiModel;
use Militer\mvcCore\Controller\aApiController;
use Militer\mvcCore\DI\Container;
use Ramsey\Uuid\Uuid;

class MainApiController extends aApiController
{
    public MainApiModel $Model;


    public function __construct(MainApiModel $Model)
    {
        parent::__construct();
        $this->Model = $Model;
    }


    public function index(array $routerData)
    {
        \extract($routerData);
        // $this->methodVerify($method);
        \method_exists($this, $action)
            ? $this->$action($query)
            : $this->Response->badRequestMessage();
    }

    public function login()
    {
        $this->csrfVerify(function ($loginData) {
            $this->User->login($loginData);
        });
    }

    public function logout()
    {
        $this->User->logout();
    }

    public function register()
    {
        $this->csrfVerify(function ($registerData) {
            $this->User->register($registerData);

            if ($this->User->checkEmail($registerData['email'])) {
                // $messages = Container::get('messages', 'register');
                // $message = $messages['impossible'];
                // $message['message'] = \str_replace('%email%', $registerData['email'], $message['message']);
                // $this->Response->sendJson($message);
                $this->sendMessage('register', 'impossible');
            } else {
                $registerData['userUuid'] = Uuid::uuid4();
                $registerData['password'] = \password_hash($registerData['password'], \PASSWORD_DEFAULT);
                $result = $this->User->register($registerData);
                $this->sendMessage('register', $result);
            }
        });
    }

    public function accessRestoreRequest()
    {
        $this->csrfVerify(function ($accessRestoreData) {
            // $accessRestoreMessages = Container::get('messages', 'accessRestore');
            $email = $accessRestoreData['email'];
            if ($this->User->checkEmail($email)) {
                if ($this->User->accessRestoreRequest($email)) {
                    // $accessRestoreMessages['success']['message'] =
                    //     \str_replace('{{email}}', $email, $accessRestoreMessages['success']['message']);
                    // $this->Response->sendJson($accessRestoreMessages['success']);
                    $this->sendMessage('accessRestore', true);
                } else {
                    // $this->Response->sendJson($accessRestoreMessages['error']);
                    // $this->sendMessage('accessRestore', 'error');
                    $this->sendMessage('accessRestore', false);
                }
            } else {
                // $this->Response->sendJson($accessRestoreMessages['noUser']);
                $this->sendMessage('accessRestore', 'noUser');
            }
        });
    }

    public function accessRestore()
    {
        $this->csrfVerify(function ($accessRestoreData) {
            $email    = $accessRestoreData['email'];
            $password = $accessRestoreData['password'];
            $this->User->accessRestore($email, $password)
                ? $this->Response->homePage()
                : $this->Response->notFoundPage();
            // if ($this->User->accessRestore($email, $password)) {
            //     $this->Response->homePage();
            // } else {
            //     $this->Response->notFoundPage();
            // }
        });
    }



    public function popup(array $query)
    {
        $popup = $query[0];
        $this->Model->popup($popup);
    }



    public function documentation()
    {
        $message = 'api_documentation';
        $this->Response->sendMessage($message);
    }
}
