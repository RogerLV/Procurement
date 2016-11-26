<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\ISimpleApprove;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TApprove;

class ManagerApprove extends ProjectStage implements ISimpleApprove
{
    use TApprove;

    protected $stageID = STAGE_ID_MANAGER_APPROVE;

    public function getNextStage()
    {
        if ($this->referrer->involveReview) {
            return new VPApprove($this->referrer);
        } else  {
            return new FileContract($this->referrer);
        }
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/managerapprove')
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