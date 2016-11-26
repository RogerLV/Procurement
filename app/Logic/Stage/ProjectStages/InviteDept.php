<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Logic\DepartmentKeeper;
use App\Models\UpdateLog as Log;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\ProjectRoleDepartment;
use App\Models\ProjectRole;

class InviteDept extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_INVITE_DEPT;

    public function getNextStage()
    {
        return new AssignMaker($this->referrer);
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/inviteDept')
            ->with('title', $this->getStageName())
            ->with('deptInfo', DepartmentKeeper::getDeptInfo())
            ->with('projectIns', $this->referrer);
    }

    public function renderInfoArea()
    {
        return view('project/display/stage/memberdept')
            ->with('deptInfo', $this->referrer->memberDepts()->with('department')->get());
    }

    public function canStageUp()
    {
        return !is_null($this->referrer->memberAmount);
    }

    public function operate($paras = null)
    {
        // add memberAmount info
        $this->referrer->memberAmount = $paras['memberCount'];
        $oldVal = $this->referrer->getOriginal();
        $this->referrer->save();
        Log::logUpdate($this->referrer, $oldVal);

        // add project dept
        $userDept = LoginUserKeeper::getUser()->getDepartmentInfo()->dept;
        $deptKeys = DepartmentKeeper::getDeptKeys();
        $paras['invitedDepts'][] = $userDept;

        foreach (array_unique($paras['invitedDepts']) as $dept) {
            if (in_array($dept, $deptKeys)) {
                $memberDept = new ProjectRoleDepartment();
                $memberDept->dept = $dept;
                $this->referrer->memberDepts()->save($memberDept);
                Log::logInsert($memberDept);
            }
        }

        // add project creator (not logging)
        $responserMemberDeptIns = ProjectRoleDepartment::where([
            ['projectID', '=', $this->referrer->id],
            ['dept', '=', $userDept]
        ])->first();
        $responser = new ProjectRole();
        $responser->lanID = $this->referrer->lanID;
        $responserMemberDeptIns->role()->save($responser);

        $this->logOperation();
    }
}