<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ReviewMeetingStage;
use App\Logic\Stage\TLogOperation;

class GenerateMinutes extends ReviewMeetingStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES;

    public function getNextStage()
    {
        return new MemberComments($this->referrer);
    }

    public function renderFunctionArea()
    {
        return view('review.display.function.generateminutes')
                ->with('title', $this->getStageName())
                ->with('reviewIns', $this->referrer)
                ->with('metaInfo', $this->referrer->metaInfo)
                ->with('topics', $this->referrer->topics()->with('topicable', 'meetingMinutesContent')->get());
    }

    public function renderInfoArea()
    {
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

        return view('review.display.info.meetingminutes')
            ->with('reviewIns', $this->referrer)
            ->with('metaInfo', $this->referrer->metaInfo)
            ->with('topics', $this->referrer->topics()->with('topicable', 'meetingMinutesContent')->get())
            ->with('memberNames', $committeeMemberNames)
            ->with('inviteeNames', $specialInviteeNames);
    }

    public function canStageUp()
    {
        $emptyTopics = $this->referrer->topics()->with('meetingMinutesContent')->get()
                        ->filter(function ($ins, $key) {
                            return empty($ins->meetingMinutesContent);
                        });
        return !empty($this->referrer->metaInfo) && $emptyTopics->isEmpty();
    }
}