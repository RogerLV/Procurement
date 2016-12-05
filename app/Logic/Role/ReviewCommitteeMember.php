<?php

namespace App\Logic\Role;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\User;
use App\Models\ReviewMeeting;

class ReviewCommitteeMember extends AbstractRole
{
    protected $roleID = 6;
    protected $roleName = ROLE_NAME_REVIEW_COMMITTEE_MEMBER;
    protected $operableStages= [
        STAGE_ID_PASS_SIGN,
        STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM,
        STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS,
    ];

    public function getCandidates()
    {
        return User::inService()->orderBy('uEngName')->get();
    }

    public function reviewMeetingVisible(ReviewMeeting $reviewMeeting)
    {
        return STAGE_ID_REVIEW_MEETING_INITIATE != $reviewMeeting->stage;
    }

    public function reviewMeetingOperable(ReviewMeeting $reviewMeetingIns)
    {
        $userLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $participated = $reviewMeetingIns->participants()
                        ->where('roleID', $this->getRoleID())
                        ->where('lanID', $userLanID)
                        ->count();

        return parent::reviewMeetingOperable($reviewMeetingIns) && $participated !=0;
    }
}