<?php

namespace App\Logic\Stage;


use App\Logic\DepartmentKeeper;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\ProjectRoleDepartment;
use App\Models\ProjectRole;
use App\Models\UpdateLog as Log;

class InviteDept extends AbstractStage
{
    protected $stageID = STAGE_ID_INVITE_DEPT;

    protected function instantiateNextStage()
    {
        return new AssignMaker($this->project);
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/inviteDept')
                    ->with('title', $this->getStageName())
                    ->with('deptInfo', DepartmentKeeper::getDeptInfo())
                    ->with('projectIns', $this->project);
    }

    public function renderInfoArea()
    {
        return view('project/display/stage/memberdept')
                ->with('deptInfo', $this->project->memberDepts()->with('department')->get());
    }

    public function canStageUp()
    {
        return !is_null($this->project->memberAmount);
    }

    public function operate($paras)
    {
        // add memberAmount info
        $this->project->memberAmount = $paras['memberCount'];
        $oldVal = $this->project->getOriginal();
        $this->project->save();
        Log::logUpdate($this->project, $oldVal);

        // add project dept
        $userDept = LoginUserKeeper::getUser()->getDepartmentInfo()->dept;
        $deptKeys = DepartmentKeeper::getDeptKeys();
        $paras['invitedDepts'][] = $userDept;

        foreach (array_unique($paras['invitedDepts']) as $dept) {
            if (in_array($dept, $deptKeys)) {
                $memberDept = new ProjectRoleDepartment();
                $memberDept->dept = $dept;
                $this->project->memberDepts()->save($memberDept);
                Log::logInsert($memberDept);
            }
        }

        // add project creator (not logging)
        $responserMemberDeptIns = ProjectRoleDepartment::where([
            ['projectID', '=', $this->project->id],
            ['dept', '=', $userDept]
        ])->first();
        $responser = new ProjectRole();
        $responser->lanID = $this->project->lanID;
        $responserMemberDeptIns->role()->save($responser);

        $this->logOperation();
    }
}