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
        STAGE_ID_INVITE_DEPT,
        STAGE_ID_ASSIGN_MAKER,
    ];

    public function getCandidates()
    {
        return User::inService()->orderBy('uEngName')->get();
    }

    public function projectOperable($projectIns)
    {
        if (parent::projectOperable($projectIns)) {

            $userDept = LoginUserKeeper::getUser()->getActiveRole()->dept;
            switch ($projectIns->stage) {
                case STAGE_ID_INVITE_DEPT:
                    return $userDept == $projectIns->dept;

                case STAGE_ID_ASSIGN_MAKER:
                    return $userDept == $projectIns->dept
                            || in_array($userDept, $projectIns->memberDepts()->pluck('dept')->toArray());

                default:
                    return false;
            }
        }

        return false;
    }
}