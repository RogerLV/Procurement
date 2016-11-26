<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\ProjectStage;

class Complete extends ProjectStage
{
    protected $stageID = STAGE_ID_COMPLETE;

    public function getNextStage()
    {
        return null;
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function operate($para = null)
    {
        return null;
    }
}