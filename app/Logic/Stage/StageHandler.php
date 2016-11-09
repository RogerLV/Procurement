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

            default:
                throw new AppException('STGHDL001', 'Data Error.');
        }
    }
}