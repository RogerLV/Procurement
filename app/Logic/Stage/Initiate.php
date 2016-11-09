<?php

namespace App\Logic\Stage;


use Config;

class Initiate extends AbstractStage
{
    protected $stageID = STAGE_ID_INITIATE;

    protected function instantiateNextStage()
    {
        return new InviteDept($this->project);
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return view('project/display/stage/basicinfo')
                ->with('project', $this->project)
                ->with('stageNames', Config::get('constants.stageNames'));
    }

    public function canStageUp()
    {
        return true;
    }

    public function operate($paras)
    {
        return null;
    }
}