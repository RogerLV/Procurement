<?php

namespace App\Logic\StageOld;


class VPApprove extends AbstractStage
{
    protected $stageID = STAGE_ID_VP_APPROVE;

    protected function instantiateNextStage()
    {
        return new Audit($this->project);
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