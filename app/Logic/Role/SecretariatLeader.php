<?php

namespace App\Logic\Role;


use App\Models\User;

class SecretariatLeader extends AbstractRole
{
    protected $roleID = 7;
    protected $roleName = ROLE_NAME_SECRETARIAT_LEADER;

    public function getCandidates()
    {
        return User::inService()->where('dept', 'FMD')->get();
    }
}