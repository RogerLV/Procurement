<?php

namespace App\Logic\Role;

use App\Models\Project;
use App\Models\User;
use App\Models\ReviewMeeting;

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
}