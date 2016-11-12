<?php

namespace App\Logic\Role;


use App\Models\User;
use App\Models\Project;
use App\Logic\LoginUser\LoginUserKeeper;

class DeptManager extends AbstractRole
{
    protected $roleID = 2;
    protected $roleName = ROLE_NAME_DEPT_MANAGER;
    protected $assignDept = true;
    protected $operableStages = [
        STAGE_ID_INVITE_DEPT,
        STAGE_ID_ASSIGN_MAKER,
        STAGE_ID_SELECT_MODE,
        STAGE_ID_RECORD,
    ];

    public function getCandidates()
    {
        return User::inService()->orderBy('uEngName')->get();
    }

    public function projectOperable(Project $projectIns)
    {
        if (parent::projectOperable($projectIns)) {

            $userDept = LoginUserKeeper::getUser()->getActiveRole()->dept;
            switch ($projectIns->stage) {
                case STAGE_ID_INVITE_DEPT:
                case STAGE_ID_SELECT_MODE:
                    return $userDept == $projectIns->dept;

                case STAGE_ID_ASSIGN_MAKER:
                    return in_array($userDept, $projectIns->memberDepts()->pluck('dept')->toArray());

                default:
                    return true;
            }
        }

        return false;
    }

    public function projectVisible(Project $projectIns)
    {
        $userDept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        return in_array($userDept, $projectIns->memberDepts()->pluck('dept')->toArray());
    }
}