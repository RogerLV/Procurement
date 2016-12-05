<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\DepartmentKeeper;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\Stage\ReviewMeetingStage;
use App\Models\StageLog;

class MemberConfirm extends ReviewMeetingStage
{
    protected $stageID = STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM;

    public function getNextStage()
    {
        return new GenerateMinutes($this->referrer);
    }

    public function renderFunctionArea()
    {
        $participants = $this->referrer->participants()->with('user')
                        ->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)->get()->keyBy('lanID');
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;

        return view('review.display.function.memberconfirm')
                ->with('title', $this->getStageName())
                ->with('participants', $participants)
                ->with('loginUserLanID', $loginUserLanID)
                ->with('deptInfo', DepartmentKeeper::getDeptInfo());
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function operate($para = null)
    {
        if ('approve' == $para['operation']) {
            $this->approveOperation($para['comment']);
        } elseif ('reject' == $para['operation']) {
            $this->rejectOperation($para['comment']);
        }
    }

    public function approveOperation($comment)
    {
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;

        $this->referrer->participants()
            ->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)
            ->where('lanID', $loginUserLanID)
            ->update(['willAttend' => true]);

        if ($this->canStageUp()) {
            $nextStageID = $this->getNextStage()->getStageID();
            $this->referrer->stage = $nextStageID;
            $this->referrer->save();

            $this->logOperation($comment, $nextStageID);
        } else {
            $this->logOperation($comment, $this->getStageID());
        }
    }

    public function rejectOperation($comment)
    {
        $this->referrer->stage = $this->getPreviousStage()->getStageID();
        $this->referrer->save();

        $toStage = $this->getPreviousStage()->getStageID();
        $this->logOperation($comment, $toStage);
    }

    public function logOperation($comment, $toStage)
    {
        $log = new StageLog();
        $log->fromStage = $this->getStageID();
        $log->toStage = $toStage;
        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $log->lanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $log->comment = $comment;
        $log->timeAt = date('Y-m-d H:i:s');

        $this->referrer->log()->save($log);
    }

    public function canStageUp()
    {
        return $this->referrer->participants()
                     ->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)
                     ->whereNull('willAttend')
                     ->count() == 0;
    }

    public function getPreviousStage()
    {
        return new Initiate($this->referrer);
    }
}