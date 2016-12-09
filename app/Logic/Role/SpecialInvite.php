<?php

namespace App\Logic\Role;

use App\Models\Project;
use App\Models\User;
use App\Models\ReviewMeeting;
use App\Logic\LoginUser\LoginUserKeeper;

class SpecialInvite extends AbstractRole
{
    protected $roleID = 11;
    protected $roleName = ROLE_NAME_SPECIAL_INVITE;
    protected $commonPages = [];

    public function getCandidates()
    {
        return User::inService()->where('dept', 'INA')->get();
    }

    public function projectVisible(Project $projectIns)
    {
        return false;
    }

    public function listProject()
    {
        return collect([]);
    }

    public function reviewMeetingVisible(ReviewMeeting $reviewMeeting)
    {
        return STAGE_ID_REVIEW_MEETING_INITIATE != $reviewMeeting->stage;
    }

    public function pendingReviewMeetingParticipate(ReviewMeeting $reviewMeeting)
    {
        // check invited at last reducing chance to run query
        return $reviewMeeting->date >= date('Y-m-d')
            && in_array($reviewMeeting->stage, $this->stages['reviewMeetingPendingParticipate'])
            && $this->reviewMeetingInvited($reviewMeeting);
    }


    private function reviewMeetingInvited(ReviewMeeting $reviewMeeting)
    {
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;

        return !is_null(
            $reviewMeeting->participants()->where([
                ['roleID', '=', ROLE_ID_SPECIAL_INVITE],
                ['lanID', '=', $loginUserLanID]
            ])->first()
        );
    }
}