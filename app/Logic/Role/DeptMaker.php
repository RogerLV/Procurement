<?php

namespace App\Logic\Role;


use App\Logic\LoginUser\LoginUserKeeper;

class DeptMaker extends AbstractRole
{
    protected $roleID = 1;
    protected $roleName = ROLE_NAME_DEPT_MAKER;
    protected $operable = false;
    protected $displayable = false;
    protected $roleSpecPages = [
        ROUTE_NAME_PROJECT_APPLY,
    ];
    protected $operableStages = [
        STAGE_ID_INVITE_DEPT
    ];

    public function getCandidates()
    {
        return [];
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