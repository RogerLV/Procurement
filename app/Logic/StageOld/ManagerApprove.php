<?php

namespace App\Logic\StageOld;


class ManagerApprove extends AbstractStage
{
    protected $stageID = STAGE_ID_MANAGER_APPROVE;

    protected function instantiateNextStage()
    {
        if ($this->project->involveReview) {
            return new VPApprove($this->project);
        } else  {
            return new FileContract($this->project);
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

    public function canStageUp()
    {
        return false;
    }

    public function operate($para)
    {
        $this->approve($para['operation'], $para['comment']);
    }

    protected function getPreviousStage()
    {
        return new Summarize($this->project);
    }
}