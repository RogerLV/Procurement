<?php

namespace App\Logic\Role;


use App\Models\User;

class ReviewCommitteeMember extends AbstractRole
{
    protected $roleID = 6;
    protected $roleName = ROLE_NAME_REVIEW_COMMITTEE_MEMBER;

    public function getCandidates()
    {
        return User::inService()->orderBy('uEngName')->get();
    }
}