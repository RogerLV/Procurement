<?php

namespace App\Logic\Stage;


class Audit extends AbstractStage
{
    protected $stageID = STAGE_ID_AUDIT;

    protected function instantiateNextStage()
    {
        return new DueDiligence($this->project);
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