<?php

namespace App\Logic\Stage;


use App\Models\UpdateLog as Log;

class Summarize extends AbstractStage
{
    protected $stageID = STAGE_ID_SUMMARIZE;

    protected function instantiateNextStage()
    {
        return new ManagerApprove($this->project);
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/summarize')
            ->with('title', $this->getStageName());
    }

    public function renderInfoArea()
    {
        return view('project/display/stage/summary')
            ->with('summary', $this->project->summary);
    }

    public function canStageUp()
    {
        return !is_null($this->project->summary);
    }

    public function operate($para)
    {
        $this->project->summary = nl2br($para['summary']);

        $oldVal = $this->project->getOriginal();
        $this->project->save();
        Log::logUpdate($this->project, $oldVal);

        $this->logOperation();
    }
}