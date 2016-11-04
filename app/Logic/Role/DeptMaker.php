<?php

namespace App\Logic\Role;


class DeptMaker extends AbstractRole
{
    protected $roleID = 1;
    protected $roleName = ROLE_NAME_DEPT_MAKER;
    protected $operable = false;
    protected $displayable = false;
    protected $roleSpecPages = [
        ROUTE_NAME_PROJECT_APPLY,
    ];

    public function getCandidates()
    {
        return [];
    }
}