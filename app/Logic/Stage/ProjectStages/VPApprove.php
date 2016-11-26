<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\ISimpleApprove;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TApprove;

class VPApprove extends ProjectStage implements ISimpleApprove
{
    use TApprove;

    protected $stageID = STAGE_ID_VP_APPROVE;

    public function getNextStage()
    {
        return new Audit($this->referrer);
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/vpapprove')
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