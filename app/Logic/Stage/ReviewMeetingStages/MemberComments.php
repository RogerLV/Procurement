<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Exceptions\AppException;
use App\Logic\Stage\ReviewMeetingStage;
use App\Models\StageLog;
use App\Logic\LoginUser\LoginUserKeeper;

class MemberComments extends ReviewMeetingStage
{
    protected $stageID = STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS;

    public function getNextStage()
    {
        return new SecretariatLeaderApprove($this->referrer);
    }

    public function renderFunctionArea()
    {
        $currentRoundLogs = $this->getCurrentRoundLogs();
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $commented = $currentRoundLogs->where('lanID', $loginUserLanID)->count() != 0;

        $participants = $this->referrer->participants()->with('user.department')->get();

        $committeeMembers = $participants->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER);
        $committeeMemberNames = [];
        foreach ($committeeMembers as $member) {
            $committeeMemberNames[] = $member->user->getDualName();
        }

        $specialInvitees = $participants->where('roleID', ROLE_ID_SPECIAL_INVITE);
        $specialInviteeNames = [];
        foreach ($specialInvitees as $entry) {
            $specialInviteeNames[] = $entry->user->getDualName()." (".$entry->user->department->deptCnName.")";
        }

        return view('review.display.function.membercomments')
                ->with('title', $this->getStageName())
                ->with('commented', $commented)
                ->with('logs', $currentRoundLogs)
                ->with('reviewIns', $this->referrer)
                ->with('metaInfo', $this->referrer->metaInfo)
                ->with('topics', $this->referrer->topics()->with('topicable', 'meetingMinutesContent')->get())
                ->with('memberNames', $committeeMemberNames)
                ->with('inviteeNames', $specialInviteeNames);
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


    private function getCurrentRoundLogs()
    {
        $lastSubmitLog = $this->referrer->log()->where([
            ['fromStage', '=', STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES],
            ['toStage', '=', STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS]
        ])->orderBy('timeAt', 'desc')->first();

        return $this->referrer->log()->with('operator')->where([
            ['fromStage', '=', $this->getStageID()],
            ['timeAt', '>', $lastSubmitLog->timeAt]
        ])->get();
    }
}