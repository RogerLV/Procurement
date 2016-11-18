<?php

namespace App\Logic\Role;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Project;

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
        STAGE_ID_RECORD,
        STAGE_ID_SUMMARIZE,
        STAGE_ID_DUE_DILIGENCE,
    ];

    public function getCandidates()
    {
        return [];
    }

    public function projectOperable(Project $projectIns)
    {
        if (parent::projectOperable($projectIns)) {

            switch ($projectIns->stage) {
                // the following stages are handled by project owner
                case STAGE_ID_INVITE_DEPT:
                case STAGE_ID_SELECT_MODE:
                case STAGE_ID_SUMMARIZE:
                case STAGE_ID_DUE_DILIGENCE:
                    return LoginUserKeeper::getUser()->getUserInfo()->lanID == $projectIns->lanID;

                default:
                    return true;
            }
        }

        return false;
    }

    public function projectVisible(Project $projectIns)
    {
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        return in_array($loginUserLanID, $projectIns->roles()->get()->pluck('lanID')->toArray())
                || $projectIns->lanID == $loginUserLanID;
    }
}