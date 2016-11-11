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
        STAGE_ID_INVITE_DEPT,
        STAGE_ID_SELECT_MODE,
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
                case STAGE_ID_SELECT_MODE:
                    return LoginUserKeeper::getUser()->getUserInfo()->lanID == $projectIns->lanID;

                default:
                    return false;
            }
        }

        return false;
    }

    public function projectVisible($projectIns)
    {
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        return in_array($loginUserLanID, $projectIns->roles()->get()->pluck('lanID')->toArray())
                || $projectIns->lanID == $loginUserLanID;
    }
}