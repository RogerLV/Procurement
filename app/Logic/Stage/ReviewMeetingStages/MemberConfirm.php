<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\Stage\IOperated;
use App\Logic\Stage\ReviewMeetingStage;
use App\Models\StageLog;

class MemberConfirm extends ReviewMeetingStage implements IOperated
{
    protected $stageID = STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM;
    protected $executer = [
        ROLE_NAME_REVIEW_COMMITTEE_MEMBER,
        ROLE_NAME_REVIEW_DIRECTOR,
        ROLE_NAME_REVIEW_VICE_DIRECTOR,
        ROLE_NAME_SPECIAL_INVITE
    ];

    public function getNextStage()
    {
        return new GenerateMinutes($this->referrer);
    }

    public function renderFunctionArea()
    {
        $loginUser = LoginUserKeeper::getUser();
        $attendInfo = $this->referrer->participants()
                            ->where('lanID', $loginUser->getUserInfo()->lanID)
                            ->where('roleID', $loginUser->getActiveRole()->roleID)
                            ->first()->willAtttend;

        return view('review.display.function.memberconfirm')
                ->with('title', $this->getStageName())
                ->with('confirmable', is_null($attendInfo));
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
        $loginUser = LoginUserKeeper::getUser();

        $this->referrer->participants()
            ->where('roleID', $loginUser->getActiveRole()->roleID)
            ->where('lanID', $loginUser->getUserInfo()->lanID)
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

        $toStage = $this->referrer->stage;
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
                     ->whereNull('willAttend')
                     ->count() == 0;
    }

    public function getPreviousStage()
    {
        return new Initiate($this->referrer);
    }

    public function renderResult()
    {
        return view('review.display.function.memberconfirm.result')
                ->with('participants', $this->referrer->participants);
    }

    public function operated()
    {
        $loginUser = LoginUserKeeper::getUser();
        return !is_null(
            $this->referrer->participants()->where([
                ['roleID', '=', $loginUser->getActiveRole()->roleID],
                ['lanID', '=', $loginUser->getUserInfo()->lanID]
            ])->first()->willAttend
        );
    }
}