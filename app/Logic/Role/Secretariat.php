<?php

namespace App\Logic\Role;


use App\Models\User;

class Secretariat extends AbstractRole
{
    protected $roleID = 4;
    protected $roleName = ROLE_NAME_SECRETARIAT;
    protected $operableStages= [
        STAGE_ID_PRETRIAL,
        STAGE_ID_AUDIT
    ];

    public function getCandidates()
    {
        return User::inService()->orderBy('uEngName')->get();
    }
}