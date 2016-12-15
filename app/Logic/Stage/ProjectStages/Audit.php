<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\ISimpleApprove;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TApprove;

class Audit extends ProjectStage implements ISimpleApprove
{
    use TApprove;

    protected $stageID = STAGE_ID_AUDIT;
    protected $executer = [
        ROLE_NAME_SECRETARIAT
    ];

    public function getNextStage()
    {
        return new DueDiligence($this->referrer);
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/audit')
            ->with('title', $this->getStageName());
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function getPreviousStage()
    {
        return new Summarize($this->referrer);
    }
}