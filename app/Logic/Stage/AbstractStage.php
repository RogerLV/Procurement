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

    abstract public function operate($params);
    abstract public function getNextStage();
    abstract public function renderFunctionArea();
    abstract public function renderInfoArea();

    public function __construct(Project $projectIns)
    {
        $this->project = $projectIns;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function logOperation($toStage = null, $comment = null)
    {
        $log = new ProjectStageLog();
        $log->fromStage = $this->stageID;
        $log->toStage = is_null($toStage) ? $this->stageID+1 : $toStage;
        $loginUser = LoginUserKeeper::getUser();
        $log->lanID = $loginUser->getUserInfo()->lanID;
        $log->comment = $comment;
        $log->timeAt = date('Y-m-d H:i:s');
        $this->project->log()->save($log);
    }

    public function getStageName()
    {
        return Config::get('constants.stageNames.'.$this->stageID);
    }

    public function getStageID()
    {
        return $this->stageID;
    }
}