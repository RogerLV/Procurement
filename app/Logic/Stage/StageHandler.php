<?php

namespace App\Logic\Stage;


class StageHandler
{
    public static function renderStageView($projectIns)
    {
        $stage = new Initiate($projectIns);

        $infoView = "";
        while ($projectIns->stage != $stage->getStageID()) {
            $infoView .= $stage->renderInfoArea();
            $stage = $stage->getNextStage();
        }

        return $infoView . $stage->renderFunctionArea();
    }
}