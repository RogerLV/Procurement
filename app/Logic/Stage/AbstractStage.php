<?php

namespace App\Logic\Stage;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\ProjectStageLog;
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
        $log = new ProjectStageLog();
        $log->fromStage = $this->stageID;
        $log->toStage = $this->stageID;
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
}