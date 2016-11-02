<?php

namespace App\Logic\Role;


use App\Models\User;

class Secretariat extends AbstractRole
{
    protected $roleID = 4;
    protected $roleName = ROLE_NAME_SECRETARIAT;

    public function getCandidates()
    {
        return User::inService()->orderBy('uEngName')->get();
    }
}