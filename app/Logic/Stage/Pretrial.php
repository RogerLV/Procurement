<?php

namespace App\Logic\Stage;


class Pretrial extends AbstractStage
{
    protected $stageID = STAGE_ID_PRETRIAL;

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