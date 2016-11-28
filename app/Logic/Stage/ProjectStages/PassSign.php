<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ProjectStage;
use App\Logic\Stage\TLogOperation;
use App\Models\SystemRole;
use App\Models\StageLog;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\DepartmentKeeper;
use Config;

class PassSign extends ProjectStage
{
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

    public function canPassSignStageUp($para = null)
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

    public function logOperation($para = null)
    {
        $log = new StageLog();
        $log->fromStage = $this->stageID;
        $log->toStage = $this->stageID;
        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $log->lanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $log->comment = $para['comment'];
        $log->timeAt = date('Y-m-d H:i:s');

        if ($this->canPassSignStageUp($para)) {
            $log->toStage = $this->getNextStage()->getStageID();

            // stage up
            $this->referrer->stage = $log->toStage;
            $this->referrer->save();
        }

        $log->data1 = $para['operation']; // This attribute is different from parent
        $this->referrer->log()->save($log);
    }

    public function operate($para = null)
    {
        $this->logOperation($para);
    }
}