<?php

namespace App\Logic\Role;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Department;
use App\Models\Project;

class DeputyCountryHead extends AbstractRole
{
    protected $roleID = 5;
    protected $roleName = ROLE_NAME_DEPUTY_COUNTRY_HEAD;
    protected $operable = false;
    protected $operableStages = [
        STAGE_ID_VP_APPROVE,
    ];

    public function getCandidates()
    {
        return [];
    }

    public function projectOperable(Project $projectIns)
    {
        if(parent::projectOperable($projectIns)) {
            $userLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
            return $projectIns->department->VPInCharge == $userLanID;
        }
    }

    public function listProject()
    {
        // get depts in charge
        $userLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $depts = Department::where('VPInCharge', $userLanID)->get()->pluck('dept')->toArray();

        return Project::join('ProjectRoleDepartments', 'Projects.id', '=', 'ProjectRoleDepartments.projectID')
                ->whereIn('ProjectRoleDepartments.dept', $depts)
                ->get();
    }
}