<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Logic\DepartmentKeeper;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\ProjectRoleDepartment;
use App\Models\ProjectRole;

class InviteDept extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_INVITE_DEPT;
    protected $launchingDept;
    protected $executer = [
        ROLE_NAME_DEPT_MANAGER,
        ROLE_NAME_PROJECT_LAUNCHER
    ];

    public function getNextStage()
    {
        return new AssignMaker($this->referrer);
    }

    public function renderFunctionArea()
    {
        return view('project/display/function/invitedept')
            ->with('title', $this->getStageName())
            ->with('deptInfo', DepartmentKeeper::getDeptInfo())
            ->with('memberDepts', $this->referrer->memberDepts()->get())
            ->with('projectIns', $this->referrer);
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        return !is_null($this->getLaunchingDept());
    }

    public function operate($paras = null)
    {
        // adding project creator rather than Initiate stage because launcher dept may be deleted
        // and rebuilt at this stage.
        if (is_null($this->referrer->roles()->where('lanID', $this->referrer->lanID)->first())) {
            $launcher = new ProjectRole();
            $launcher->lanID = $this->referrer->lanID;
            $this->getLaunchingDept()->role()->save($launcher);
        }

        $this->logOperation();
    }


    private function getLaunchingDept()
    {
        if (is_null($this->launchingDept)) {
            $this->launchingDept = ProjectRoleDepartment::where([
                ['projectID', '=', $this->referrer->id],
                ['dept', '=', LoginUserKeeper::getUser()->getActiveRole()->dept]
            ])->first();
        }

        return $this->launchingDept;
    }
}