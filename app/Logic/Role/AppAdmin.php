<?php

namespace App\Logic\Role;


use App\Models\User;
use App\Models\Project;
use App\Models\ReviewMeeting;

class AppAdmin extends AbstractRole
{
    protected $roleID = 9;
    protected $roleName = ROLE_NAME_APP_ADMIN;

    public function getCandidates()
    {
        return User::inService()->where('dept', 'FMD')->get();
    }

    public function projectOperable(Project $projectIns)
    {
        return true;
    }

    public function reviewMeetingVisible(ReviewMeeting $reviewMeeting)
    {
        return true;
    }

    public function reviewMeetingOperable(ReviewMeeting $reviewMeetingIns)
    {
        return true;
    }
}