<?php

namespace App\Logic\Role;


use App\Models\ReviewMeeting;

class SystemAdmin extends AbstractRole
{
    protected $roleID = 10;
    protected $roleName = ROLE_NAME_SYSTEM_ADMIN;
    protected $operable = false;
    protected $displayable = false;
    protected $operableStages = [];

    public function getCandidates()
    {
        return [];
    }

    public function reviewMeetingVisible(ReviewMeeting $reviewMeeting)
    {
        return true;
    }
}