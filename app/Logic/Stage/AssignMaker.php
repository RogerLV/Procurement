<?php

namespace App\Logic\Stage;


class AssignMaker extends AbstractStage
{
    protected $stageID = STAGE_ID_ASSIGN_MAKER;

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

    public function operate($paras)
    {
        return null;
    }
}