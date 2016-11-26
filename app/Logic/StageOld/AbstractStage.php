<?php

namespace App\Logic\StageOld;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\StageLog;
use App\Models\Project;
use Config;

abstract class AbstractStage
{
    protected $project;
    protected $stageID;
    protected $nextStage;

    abstract protected function instantiateNextStage();
    abstract public function renderFunctionArea();
    abstract public function renderInfoArea();
    abstract public function canStageUp();
    abstract public function operate($params);

    public function __construct(Project $projectIns)
    {
        $this->project = $projectIns;
    }

    public function getNextStage()
    {
        if (is_null($this->nextStage)) {
            $this->nextStage = $this->instantiateNextStage();
        }

        return $this->nextStage;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getStageName()
    {
        return Config::get('constants.stageNames.'.$this->stageID);
    }

    public function getStageID()
    {
        return $this->stageID;
    }

    public function logOperation($comment = null)
    {
        $log = new StageLog();
        $log->fromStage = $this->stageID;
        $log->toStage = $this->stageID;
        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->lanID;
        $log->lanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $log->comment = $comment;
        $log->timeAt = date('Y-m-d H:i:s');

        if ($this->canStageUp()) {
            $log->toStage = $this->getNextStage()->getStageID();

            // stage up
            $this->project->stage = $log->toStage;
            $this->project->save();
        }

        $this->project->log()->save($log);
    }

    protected function approve($operation, $comment)
    {
        // directly log down the operation rather than call log function
        $log = new StageLog();
        $log->fromStage = $this->stageID;
        $log->toStage = $this->stageID;
        if ('approve' == $operation) {
            $nextStageID = $this->getNextStage()->getStageID();
            $log->toStage = $nextStageID;
            $this->project->stage = $nextStageID;
        } elseif ('reject' == $operation) {
            $previousStageID = $this->getPreviousStage()->getStageID();
            $log->toStage = $previousStageID;
            $this->project->stage = $previousStageID;
        }
        $this->project->save();

        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $log->lanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $log->comment = $comment;
        $log->timeAt = date('Y-m-d H:i:s');
        $this->project->log()->save($log);
    }

    protected function getPreviousStage()
    {
        return null;
    }
}