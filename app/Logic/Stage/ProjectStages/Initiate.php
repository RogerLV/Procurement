<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use Config;

class Initiate extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_INITIATE;

    public function getNextStage()
    {
        return new InviteDept($this->referrer);
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return view('project/display/stage/basicinfo')
            ->with('project', $this->referrer)
            ->with('stageNames', Config::get('constants.stageNames'));
    }

    public function canStageUp()
    {
        return true;
    }
}