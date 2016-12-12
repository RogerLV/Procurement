<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\ProjectStage;
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
        $signs = $this->getCurrentRoundLogs()->keyBy('lanID');
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
            $approveCount = $this->getCurrentRoundLogs()->where('data1', 'approve')->count();

            return $approveCount ==  SystemRole::where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)->count()-1;
        }

        return false;
    }

    public function logOperation($para = null)
    {
        $log = $this->getBasicLogInfo($para);

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

    public function logFromReviewMeeting($para)
    {
        $log = $this->getBasicLogInfo($para);
        $toStageID = 'approve' == $para['operation'] ?
                     $this->getNextStage()->getStageID() :
                     $this->getPreviousStage()->getStageID();

        // do not record data1 value
        $log->toStage = $toStageID;
        $this->referrer->stage = $toStageID;

        $this->referrer->save();
        $this->referrer->log()->save($log);
    }

    public function getPreviousStage()
    {
        return new SelectMode($this->referrer);
    }

    private function getBasicLogInfo($para)
    {
        $log = new StageLog();
        $log->fromStage = $this->stageID;
        $log->toStage = $this->stageID;
        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $log->lanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $log->comment = $para['comment'];
        $log->timeAt = date('Y-m-d H:i:s');

        return $log;
    }

    private function getCurrentRoundLogs()
    {
        //get last submit
        $lastSumbitRecord = $this->referrer->log()->where([
            ['fromStage', '=', STAGE_ID_SELECT_MODE],
            ['toStage', '>', STAGE_ID_SELECT_MODE]
        ])->orderBy('id', 'DESC')->first();

        return $this->referrer->log()->where([
            ['fromStage', '=', $this->getStageID()],
            ['timeAt', '>', $lastSumbitRecord->timeAt]
        ])->get();
    }
}