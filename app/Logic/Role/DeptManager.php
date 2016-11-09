<?php

namespace App\Logic\Role;


use App\Models\User;
use App\Logic\LoginUser\LoginUserKeeper;

class DeptManager extends AbstractRole
{
    protected $roleID = 2;
    protected $roleName = ROLE_NAME_DEPT_MANAGER;
    protected $assignDept = true;
    protected $operableStages = [
        STAGE_ID_INVITE_DEPT
    ];

    public function getCandidates()
    {
        return User::inService()->orderBy('uEngName')->get();
    }

    public function projectOperable($projectIns)
    {
        if (parent::projectOperable($projectIns)) {

            switch ($projectIns->stage) {
                case STAGE_ID_INVITE_DEPT:
                    return LoginUserKeeper::getUser()->getActiveRole()->dept == $projectIns->dept;

                default:
                    return false;
            }
        }

        return false;
    }
}