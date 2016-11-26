<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Models\UpdateLog as Log;

class Summarize extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_SUMMARIZE;

    public function getNextStage()
    {
        return new ManagerApprove($this->referrer);
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/summarize')
            ->with('title', $this->getStageName());
    }

    public function renderInfoArea()
    {
        return view('project/display/stage/summary')
            ->with('summary', $this->referrer->summary);
    }
    public function canStageUp()
    {
        return !is_null($this->referrer->summary);
    }

    public function operate($para =null)
    {
        $this->referrer->summary = nl2br($para['summary']);

        $oldVal = $this->referrer->getOriginal();
        $this->referrer->save();
        Log::logUpdate($this->referrer, $oldVal);

        $this->logOperation();
    }
}