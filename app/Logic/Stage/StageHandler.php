<?php

namespace App\Logic\Stage;


use App\Exceptions\AppException;
use App\Logic\LoginUser\LoginUserKeeper;
use Gate;

class StageHandler
{
    public static function renderStageView($projectIns)
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

    public static function getStageIns($projectIns)
    {
        switch ($projectIns->stage) {
            case STAGE_ID_INVITE_DEPT: return new InviteDept($projectIns);
            case STAGE_ID_ASSIGN_MAKER: return new AssignMaker($projectIns);
            case STAGE_ID_SELECT_MODE: return new SelectMode($projectIns);
            case STAGE_ID_PRETRIAL: return new Pretrial($projectIns);
            case STAGE_ID_PASS_SIGN: return new PassSign($projectIns);
            case STAGE_ID_RECORD: return new Record($projectIns);

            default:
                throw new AppException('STGHDL001', 'Data Error.');
        }
    }
}