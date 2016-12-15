<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Exceptions\AppException;
use App\Logic\Stage\IOperated;
use App\Logic\Stage\ReviewMeetingStage;
use App\Models\StageLog;
use App\Logic\LoginUser\LoginUserKeeper;

class MemberComments extends ReviewMeetingStage implements IOperated
{
    protected $stageID = STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS;
    protected $executer = [
        ROLE_NAME_REVIEW_COMMITTEE_MEMBER
    ];

    public function getNextStage()
    {
        return new SecretariatLeaderApprove($this->referrer);
    }

    public function renderFunctionArea()
    {
        $currentRoundLogs = $this->getCurrentRoundLogs();
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $commented = $currentRoundLogs->where('lanID', $loginUserLanID)->count() != 0;

        return view('review.display.function.membercomments')
                ->with('title', $this->getStageName())
                ->with('commented', $commented)
                ->with('logs', $currentRoundLogs);
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function operate($para = null)
    {
        $this->logOperation($para['operation'], $para['comment']);
    }

    public function logOperation($operation, $comment)
    {
        // check if already logged
        $logs = $this->getCurrentRoundLogs();
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        if ($logs->where('lanID', $loginUserLanID)->count() != 0) {
            throw new AppException('STGMMBCMT001', ERROR_MESSAGE_ALREADY_COMMENTED);
        }

        $log = new StageLog();
        $log->fromStage = $this->getStageID();
        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $log->lanID = $loginUserLanID;
        $log->data1 = $operation;
        $log->comment = $comment;
        $log->timeAt = date('Y-m-d H:i:s');

        // get toStage value
        $members = $this->referrer->participants()->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)->get();

        $log->toStage = $this->getStageID();
        if ($members->count() == $logs->count()+1) {
            $othersApproved = $logs->where('data1', 'reject')->count() == 0;
            $IApprove = $operation == 'approve';
            $log->toStage = $othersApproved && $IApprove ?
                            $this->getNextStage()->getStageID() :
                            $this->getPreviousStage()->getStageID();
            $this->referrer->stage = $log->toStage;
            $this->referrer->save();
        }

        $this->referrer->log()->save($log);
    }

    public function getPreviousStage()
    {
        return new GenerateMinutes($this->referrer);
    }

    public function operated()
    {
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $operatedLog = $this->getCurrentRoundLogs()->where('lanID', $loginUserLanID)->first();
        return !is_null($operatedLog);
    }


    private function getCurrentRoundLogs()
    {
        $lastSubmitLog = $this->referrer->log
            ->whereLoose('fromStage', STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES)
            ->whereLoose('toStage', STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS)
            ->sortByDesc('timeAt')->first();

        return $this->referrer->log
                    ->whereLoose('fromStage', $this->getStageID())
                    ->filter(function ($ins) use ($lastSubmitLog) {
                        return $ins->id > $lastSubmitLog->id;
                    });
    }
}