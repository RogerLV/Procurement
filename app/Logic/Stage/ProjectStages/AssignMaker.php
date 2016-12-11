<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Models\ProjectRoleDepartment;
use App\Models\User;
use App\Logic\DepartmentKeeper;
use App\Logic\LoginUser\LoginUserKeeper;

class AssignMaker extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_ASSIGN_MAKER;

    public function getNextStage()
    {
        return new SelectMode($this->referrer);
    }

    public function renderFunctionArea()
    {
        $userDept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $memberDepts = ProjectRoleDepartment::with('role')->where('projectID', $this->referrer->id)->get();
        $projectRoles = $this->referrer->roles()->get();
        $userInfo = User::whereIn('lanID', $projectRoles->pluck('lanID'))->get()->keyBy('lanID');

        $log = $this->referrer->log()->where([
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
        $memberDepts = ProjectRoleDepartment::with('role')->where('projectID', $this->referrer->id)->get();
        $projectRoles = $this->referrer->roles()->get();
        $userInfo = User::whereIn('lanID', $projectRoles->pluck('lanID'))->get()->keyBy('lanID');

        return view('project/display/stage/assignmaker')
            ->with('deptInfo', DepartmentKeeper::getDeptInfo())
            ->with('memberDeptsWithRoles', $memberDepts->keyBy('dept'))
            ->with('userInfo', $userInfo);
    }

    public function canStageUp()
    {
        $stageLogCount = $this->referrer->log()->where([
            ['fromStage', '=', STAGE_ID_ASSIGN_MAKER],
            ['toStage', '=', STAGE_ID_ASSIGN_MAKER]
        ])->count();

        return $stageLogCount == $this->referrer->memberDepts()->count() - 1;
    }

    public function canLog()
    {
        $userDept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $roleCount = $this->referrer->memberDepts()->where('dept', $userDept)->first()->role()->count();

        return $roleCount !=0;
    }
}