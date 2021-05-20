<?php

namespace Main\Models;

use Militer\mvcCore\Model\aApiModel;

class MainApiModel extends aApiModel
{
    public function __construct()
    {
        parent::__construct();
        $this->popupsTable = self::MAIN_POPUPS_TABLE;
        $this->views = \MAIN_VIEWS;
    }


    public function popup(string $popup)
    {
        $this->renderPopup($popup);
    }
}
