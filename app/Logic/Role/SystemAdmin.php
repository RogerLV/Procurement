<?php

namespace App\Logic\Role;


class SystemAdmin extends AbstractRole
{
    protected $roleID = 10;
    protected $roleName = ROLE_NAME_SYSTEM_ADMIN;
    protected $operable = false;
    protected $displayable = false;
    protected $operableStages = [];

    public function getCandidates()
    {
        return [];
    }
}