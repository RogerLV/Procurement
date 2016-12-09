<?php

namespace App\Logic\Role;


use App\Models\User;
use App\Models\ReviewMeeting;

class SecretariatLeader extends AbstractRole
{
    protected $roleID = 7;
    protected $roleName = ROLE_NAME_SECRETARIAT_LEADER;
    protected $operableStages = [
        STAGE_ID_REVIEW_MEETING_SECRETARIAT_LEADER_APPROVE,
    ];
    protected $roleSpecPages = [
        'ReviewMeetingList'
    ];

    public function getCandidates()
    {
        return User::inService()->where('dept', 'FMD')->get();
    }

    public function reviewMeetingVisible(ReviewMeeting $reviewMeeting)
    {
        return STAGE_ID_REVIEW_MEETING_INITIATE != $reviewMeeting->stage;
    }

    public function pendingReviewMeetingParticipate(ReviewMeeting $reviewMeeting)
    {
        return $reviewMeeting->date >= date('Y-m-d')
        && in_array($reviewMeeting->stage, $this->stages['reviewMeetingPendingParticipate']);
    }

    public function pendingReviewMeetingProcess(ReviewMeeting $reviewMeeting)
    {
        return $reviewMeeting->stage == STAGE_ID_REVIEW_MEETING_SECRETARIAT_LEADER_APPROVE;
    }
}