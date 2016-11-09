<?php

namespace App\Logic\Stage;


use App\Logic\DepartmentKeeper;
use App\Models\ProjectRoleDepartment;

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
                    ->with('deptInfo', DepartmentKeeper::getDeptInfo());
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
        $this->project->save();

        // add project dept if not null
        if (!is_null($paras['invitedDepts'])) {
            $deptKeys = DepartmentKeeper::getDeptKeys();
            foreach ($paras['invitedDepts'] as $dept) {
                if (in_array($dept, $deptKeys)) {
                    $memberDept = new ProjectRoleDepartment();
                    $memberDept->dept = $dept;
                    $this->project->memberDepts()->save($memberDept);
                }
            }
        }

        $this->logOperation();
    }
}