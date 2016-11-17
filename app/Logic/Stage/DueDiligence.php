<?php

namespace App\Logic\Stage;


class DueDiligence extends AbstractStage
{
    protected $stageID = STAGE_ID_DUE_DILIGENCE;

    protected function instantiateNextStage()
    {
        return new Review($this->project);
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        return false;
    }

    public function operate($para)
    {
        return null;
    }
}