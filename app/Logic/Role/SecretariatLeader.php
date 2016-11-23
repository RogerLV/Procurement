<?php

namespace App\Logic\Role;


use App\Models\User;

class SecretariatLeader extends AbstractRole
{
    protected $roleID = 7;
    protected $roleName = ROLE_NAME_SECRETARIAT_LEADER;
    protected $operableStages = [
        STAGE_ID_REVIEW_MEETING_SECRETARIAT_LEADER_APPROVE,
    ];

    public function getCandidates()
    {
        return User::inService()->where('dept', 'FMD')->get();
    }
}