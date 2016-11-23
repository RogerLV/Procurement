<?php

namespace App\Logic\Role;


class ReviewDirector extends AbstractRole
{
    protected $roleID = 8;
    protected $roleName = ROLE_NAME_REVIEW_DIRECTOR;
    protected $operable = false;
    protected $operableStages = [
        STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE
    ];

    public function getCandidates()
    {
        return [];
    }
}