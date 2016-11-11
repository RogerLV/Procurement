<?php

namespace App\Logic\Stage;


use App\Logic\DepartmentKeeper;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\ProjectRoleDepartment;
use App\Models\User;

class AssignMaker extends AbstractStage
{
    protected $stageID = STAGE_ID_ASSIGN_MAKER;

    protected function instantiateNextStage()
    {
        return new SelectMode($this->project);
    }

    public function renderFunctionArea()
    {
        $userDept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $memberDepts = ProjectRoleDepartment::with('role')->where('projectID', $this->project->id)->get();
        $projectRoles = $this->project->roles()->get();
        $userInfo = User::whereIn('lanID', $projectRoles->pluck('lanID'))->get()->keyBy('lanID');

        $log = $this->project->log()->where([
            ['fromStage', '=', $this->stageID],
            ['dept', '=', $userDept]
        ])->first();
        $assignable = is_null($log);

        return view('project/display/function/assignmaker')
                ->with('title', $this->getStageName())
                ->with('candidates', User::inService()->where('dept', $userDept)->get()->keyBy('lanID'))
                ->with('deptInfo', DepartmentKeeper::getDeptInfo())
                ->with('memberDeptsWithRoles', $memberDepts->keyBy('dept'))
                ->with('userDept', $userDept)
                ->with('userInfo', $userInfo)
                ->with('assignable', $assignable);
    }

    public function renderInfoArea()
    {
        $memberDepts = ProjectRoleDepartment::with('role')->where('projectID', $this->project->id)->get();
        $projectRoles = $this->project->roles()->get();
        $userInfo = User::whereIn('lanID', $projectRoles->pluck('lanID'))->get()->keyBy('lanID');

        return view('project/display/stage/assignmaker')
                ->with('deptInfo', DepartmentKeeper::getDeptInfo())
                ->with('memberDeptsWithRoles', $memberDepts->keyBy('dept'))
                ->with('userInfo', $userInfo);
    }

    public function canStageUp()
    {
        $logCount = $this->project->log()->where('fromStage', $this->stageID)->count();
        $memberDeptCount = $this->project->memberDepts()->count();
        return $logCount == $memberDeptCount-1;
    }

    public function operate($paras = null)
    {
        $this->logOperation();
    }
}