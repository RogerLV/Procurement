<?php

namespace App\Logic\Stage;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\ProjectStageLog;
use Config;

abstract class AbstractStage
{
    protected $project;
    protected $stageID;

    abstract public function operate($params);

    public function getProject()
    {
        return $this->project;
    }

    public function logOperation($fromStage = null, $comment = null)
    {
        $log = new ProjectStageLog();
        $log->projectID = $this->project->id;
        $log->fromStage = is_null($fromStage) ? $this->stageID - 1 : $fromStage;
        $log->toStage = $this->stageID;
        $loginUser = LoginUserKeeper::getUser();
        $log->lanID = $loginUser->getUserInfo()->lanID;
        $log->comment = $comment;
        $log->timeAt = date('Y-m-d H:i:s');
        $log->save();
    }

    public function getStageName()
    {
        return Config::get('constants.stageNames.'.$this->stageID);
    }
}