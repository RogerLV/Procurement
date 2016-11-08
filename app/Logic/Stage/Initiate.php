<?php

namespace App\Logic\Stage;


class Initiate extends AbstractStage
{
    protected $stageID = STAGE_ID_INITIATE;

    public function operate($paras)
    {
        return null;
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return View('project/display/stage/basicinfo')
                ->with('project', $this->project);
    }

    public function getNextStage()
    {
        return new InviteDept($this->project);
    }
}