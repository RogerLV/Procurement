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
        ROLE_NAME_REVIEW_COMMITTEE_MEMBER,
        ROLE_NAME_REVIEW_VICE_DIRECTOR,
        ROLE_NAME_REVIEW_DIRECTOR
    ];

    public function getNextStage()
    {
        return new SecretariatLeaderApprove($this->referrer);
    }

    public function renderFunctionArea()
    {
        $currentRoundLogs = $this->getCurrentRoundLogs();
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;

        return view('review.display.function.membercomments')
                ->with('title', $this->getStageName())
                ->with('commented', $this->operated())
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
        if ($this->operated()) {
            throw new AppException('STGMMBCMT001', ERROR_MESSAGE_ALREADY_COMMENTED);
        }

        $loginUser = LoginUserKeeper::getUser();

        $log = new StageLog();
        $log->fromStage = $this->getStageID();
        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $log->lanID = $loginUser->getUserInfo()->lanID;
        $log->roleID = $loginUser->getActiveRole()->roleID;
        $log->data1 = $operation;
        $log->comment = $comment;
        $log->timeAt = date('Y-m-d H:i:s');

        // get toStage value
        $members = $this->referrer->participants()->whereIn('roleID', [
            ROLE_ID_REVIEW_COMMITTEE_MEMBER,
            ROLE_ID_REVIEW_VICE_DIRECTOR,
            ROLE_ID_REVIEW_DIRECTOR
        ])->get();

        $log->toStage = $this->getStageID();

        $logs = $this->getCurrentRoundLogs();
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
        $loginUser = LoginUserKeeper::getUser();
        $operatedLog = $this->getCurrentRoundLogs()->where(
            'lanID',
            $loginUser->getUserInfo()->lanID
        )->whereLoose(
            'roleID',
            $loginUser->getActiveRole()->roleID
        )->first();

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