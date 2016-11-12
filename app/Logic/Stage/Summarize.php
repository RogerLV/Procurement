<?php

namespace App\Logic\Stage;


class Summarize extends AbstractStage
{
    protected $stageID = STAGE_ID_SUMMARIZE;

    protected function instantiateNextStage()
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

    public function canStageUp()
    {
        return false;
    }

    public function operate($para)
    {
        return null;
    }
}