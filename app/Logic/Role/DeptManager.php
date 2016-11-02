<?php

namespace App\Logic\Role;


use App\Models\User;

class DeptManager extends AbstractRole
{
    protected $roleID = 2;
    protected $roleName = ROLE_NAME_DEPT_MANAGER;
    protected $assignDept = true;

    public function getCandidates()
    {
        return User::inService()->orderBy('uEngName')->get();
    }
}