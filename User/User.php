<?php

namespace User;

use Militer\mvcCore\User\aUser;

class User extends aUser
{


    public function __construct()
    {
        parent::__construct();

    }


    public function test()
    {
        $admins = $this->getAdminsData();
        \prd($admins, '$admins');
    }
}
