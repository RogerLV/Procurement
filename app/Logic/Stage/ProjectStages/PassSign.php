<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Models\SystemRole;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\DepartmentKeeper;
use Config;

class PassSign extends ProjectStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_PASS_SIGN;

    public function getNextStage()
    {
        return new Record($this->referrer);
    }

    public function renderFunctionArea()
    {
        $fullCommittee = SystemRole::with('user', 'department')
            ->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)
            ->get()
            ->keyBy('lanID');
        $signs = $this->referrer->log()
            ->where('fromStage', $this->getStageID())
            ->get()
            ->keyBy('lanID');
        $userLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;

        return view('project/display/function/passsign')
            ->with('title', $this->getStageName())
            ->with('signs', $signs)
            ->with('fullCommittee', $fullCommittee)
            ->with('signable', !$signs->has($userLanID))
            ->with('deptInfo', DepartmentKeeper::getDeptInfo())
            ->with('passSignValues', Config::get('constants.passSignValues'));
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp($para = null)
    {
        if ('approve' == $para['operation']) {
            $approveCount = $this->referrer->log()->where([
                ['fromStage', '=', $this->getStageID()],
                ['data1', '=', 'approve']
            ])->count();

            return $approveCount ==  SystemRole::where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)->count()-1;
        }

        return false;
    }
}