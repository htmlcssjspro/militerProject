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
        $this->methodVerify($method);
        \method_exists($this, $action)
            ? $this->$action($query)
            : $this->Response->badRequestMessage();
    }


    public function login()
    {
        $this->csrfVerify(function ($adminLoginData) {
            $this->User->adminLogin($adminLoginData);
        });
    }

    public function logout()
    {
        $this->User->adminLogout();
    }


    public function preferences()
    {
        $this->csrfVerify(function ($preferencesData) {
            $this->Model->preferences($preferencesData);
        });
    }

    public function adminPasswordChange()
    {
        $this->csrfVerify(function ($adminPasswordChangeData) {
            $this->User->adminPasswordChange($adminPasswordChangeData);
        });
    }

    public function addNewAdmin()
    {
        $this->csrfVerify(function ($newAdminData) {
            $this->User->addAdmin($newAdminData);
        });
    }

    public function activateAdmin()
    {
        \method();
        $POST = $this->Request->getPOST();
        \pr($POST, '$POST');
        \prd($_POST, '$_POST');
    }



    public function test()
    {
        $this->User->test();
    }

}
