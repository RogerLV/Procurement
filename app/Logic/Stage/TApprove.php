<?php

namespace App\Logic\Stage;


use App\Models\StageLog;
use App\Logic\LoginUser\LoginUserKeeper;

trait TApprove
{
    public function approve($operation, $comment)
    {
        $log = new StageLog();
        $log->fromStage = $this->stageID;
        $log->toStage = $this->stageID;
        if ('approve' == $operation) {
            $nextStageID = $this->getNextStage()->getStageID();
            $log->toStage = $nextStageID;
            $this->referrer->stage = $nextStageID;
        } elseif ('reject' == $operation) {
            $previousStageID = $this->getPreviousStage()->getStageID();
            $log->toStage = $previousStageID;
            $this->referrer->stage = $previousStageID;
        }
        $this->referrer->save();

        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $log->lanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $log->comment = $comment;
        $log->timeAt = date('Y-m-d H:i:s');
        $this->referrer->log()->save($log);
    }

    public function operate($para = null)
    {
        $this->approve($para['operation'], $para['comment']);
    }
}