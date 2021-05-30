<?php

namespace Admin\Models;

use Militer\mvcCore\Model\aApiModel;

class AdminApiModel extends aApiModel
{


    public function __construct()
    {
        parent::__construct();
    }


    public function preferences(array $preferencesData)
    {
        $this->adminCheck();
        \extract($preferencesData);

        $this->Response->sendResponse('adminPreferences', true);
        $this->Response->sendResponse('adminPreferences', false);
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
