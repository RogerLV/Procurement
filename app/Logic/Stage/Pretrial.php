<?php

namespace App\Logic\Stage;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\ProjectStageLog;

class Pretrial extends AbstractStage
{
    protected $stageID = STAGE_ID_PRETRIAL;

    protected function instantiateNextStage()
    {
        if ('OpenTender' == $this->project->approach) {
            return new Record($this->project);
        } else {
            return new PassSign($this->project);
        }
    }

    public function renderFunctionArea()
    {
        $document = $this->project->document()
                         ->where('type', DOC_TYPE_PROCUREMENT_APPROACH_APPLICATION)
                         ->orderBy('id', 'desc')
                         ->first();

        return view('project/display/function/pretrial')
                ->with('title', $this->getStageName())
                ->with('project', $this->project)
                ->with('procurementMethodReport', $document);
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

        // directly log down the operation rather than call log function
        $log = new ProjectStageLog();
        $log->fromStage = $this->stageID;
        $log->toStage = $this->stageID;
        if ('approve' == $para['operation']) {
            $nextStageID = $this->getNextStage()->getStageID();
            $log->toStage = $nextStageID;
            $this->project->stage = $nextStageID;
        } elseif ('reject' == $para['operation']) {
            $previousStageID = $this->getPreviousStage()->getStageID();
            $log->toStage = $previousStageID;
            $this->project->stage = $previousStageID;
        }
        $this->project->save();

        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $log->lanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $log->comment = $para['comment'];
        $log->timeAt = date('Y-m-d H:i:s');
        $this->project->log()->save($log);
    }

    private function getPreviousStage()
    {
        return new SelectMode($this->project);
    }
}