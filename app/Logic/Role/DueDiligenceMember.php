<?php

namespace App\Logic\Role;


use App\Models\User;

class DueDiligenceMember extends AbstractRole
{
    protected $roleID = 3;
    protected $roleName = ROLE_NAME_DUE_DILIGENCE_MEMBER;

    public function getCandidates()
    {
        return User::inService()->orderBy('uEngName')->get();
    }
}