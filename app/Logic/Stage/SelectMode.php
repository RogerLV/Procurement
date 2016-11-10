<?php

namespace App\Logic\Stage;


class SelectMode extends AbstractStage
{
    protected $stageID = STAGE_ID_SELECT_MODE;

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

    public function operate($paras = null)
    {
        return null;
    }
}