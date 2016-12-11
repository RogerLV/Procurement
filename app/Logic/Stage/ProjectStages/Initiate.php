<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Models\ProjectRoleDepartment;
use Config;

class Initiate extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_INITIATE;

    public function getNextStage()
    {
        return new InviteDept($this->referrer);
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return view('project/display/stage/basicinfo')
            ->with('project', $this->referrer)
            ->with('stageNames', Config::get('constants.stageNames'));
    }

    public function canStageUp()
    {
        return true;
    }

    public function operate($para = null)
    {
        // add self department
        $memberDept = new ProjectRoleDepartment();
        $memberDept->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $memberDept->memberAmount = 3; //default

        $this->referrer->memberDepts()->save($memberDept);

        $this->logOperation();
    }
}