<?php

namespace App\Logic\StageOld;


class Review extends AbstractStage
{
    protected $stageID = STAGE_ID_REVIEW;

    protected function instantiateNextStage()
    {
        return new FileContract($this->project);
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