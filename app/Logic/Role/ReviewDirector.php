<?php

namespace App\Logic\Role;


use App\Models\ReviewMeeting;

class ReviewDirector extends AbstractRole
{
    protected $roleID = 8;
    protected $roleName = ROLE_NAME_REVIEW_DIRECTOR;
    protected $operable = false;
    protected $operableStages = [
        STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE
    ];
    protected $roleSpecPages = [
        'ReviewMeetingList'
    ];

    public function getCandidates()
    {
        return [];
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
}