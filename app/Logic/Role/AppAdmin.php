<?php

namespace App\Logic\Role;


use App\Models\User;

class AppAdmin extends AbstractRole
{
    protected $roleID = 9;
    protected $roleName = ROLE_NAME_APP_ADMIN;

    public function getCandidates()
    {
        return User::inService()->where('dept', 'FMD')->get();
    }

    public function projectOperable($projectIns)
    {
        return true;
    }
}