<?php

namespace App\Logic\Stage;


use App\Logic\DepartmentKeeper;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\SystemRole;
use App\Models\ProjectStageLog;
use Config;

class PassSign extends AbstractStage
{
    protected $stageID = STAGE_ID_PASS_SIGN;

    protected function instantiateNextStage()
    {
        return new Record($this->project);
    }

    public function renderFunctionArea()
    {
        $fullCommittee = SystemRole::with('user', 'department')
                        ->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)
                        ->get()
                        ->keyBy('lanID');
        $signs = $this->project->log()
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

    public function canStageUp()
    {
        return false;
    }

    public function canPassSignStageUp($para = null)
    {
        if ('approve' == $para['operation']) {
            $approveCount = $this->project->log()->where([
                ['fromStage', '=', $this->getStageID()],
                ['data1', '=', 'approve']
            ])->count();

            return $approveCount ==  SystemRole::where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)->count()-1;
        }

        return false;
    }

    public function operate($para)
    {
        $this->logOperation($para);
    }

    public function logOperation($para = null)
    {
        $log = new ProjectStageLog();
        $log->fromStage = $this->stageID;
        $log->toStage = $this->stageID;
        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $log->lanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $log->comment = $para['comment'];
        $log->timeAt = date('Y-m-d H:i:s');

        if ($this->canPassSignStageUp($para)) {
            $log->toStage = $this->getNextStage()->getStageID();

            // stage up
            $this->project->stage = $log->toStage;
            $this->project->save();
        }

        $log->data1 = $para['operation']; // This attribute is different from parent
        $this->project->log()->save($log);
    }
}