<?php

namespace App\Logic\StageOld;


use App\Exceptions\AppException;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Project;
use Gate;

class StageHandler
{
    public static function renderStageView(Project $projectIns)
    {
        $stage = new Initiate($projectIns);

        $infoView = "";
        while ($projectIns->stage != $stage->getStageID() && !is_null($stage)) {
            $infoView .= $stage->renderInfoArea();
            $stage = $stage->getNextStage();
        }

        $functionView = "";
        if (Gate::forUser(LoginUserKeeper::getUser())->allows('project-operable', $projectIns)) {
            $functionView = $stage->renderFunctionArea();
        }

        return $infoView . $functionView;
    }

    public static function getStageIns(Project $projectIns)
    {
        switch ($projectIns->stage) {
            case STAGE_ID_INVITE_DEPT: return new InviteDept($projectIns);
            case STAGE_ID_ASSIGN_MAKER: return new AssignMaker($projectIns);
            case STAGE_ID_SELECT_MODE: return new SelectMode($projectIns);
            case STAGE_ID_PRETRIAL: return new Pretrial($projectIns);
            case STAGE_ID_PASS_SIGN: return new PassSign($projectIns);
            case STAGE_ID_RECORD: return new Record($projectIns);
            case STAGE_ID_SUMMARIZE: return new Summarize($projectIns);
            case STAGE_ID_MANAGER_APPROVE: return new ManagerApprove($projectIns);
            case STAGE_ID_VP_APPROVE: return new VPApprove($projectIns);
            case STAGE_ID_AUDIT: return new Audit($projectIns);
            case STAGE_ID_DUE_DILIGENCE: return new DueDiligence($projectIns);
            case STAGE_ID_REVIEW: return new Review($projectIns);
            case STAGE_ID_FILE_CONTRACT: return new FileContract($projectIns);
            case STAGE_ID_COMPLETE: return new Complete($projectIns);

            default:
                throw new AppException('STGHDL001');
        }
    }
}