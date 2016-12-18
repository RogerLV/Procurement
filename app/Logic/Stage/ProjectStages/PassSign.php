<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\Stage\ProjectStage;
use App\Models\SystemRole;
use App\Models\StageLog;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\MeetingMinutesHandler;
use Config;

class PassSign extends ProjectStage
{
    protected $stageID = STAGE_ID_PASS_SIGN;
    protected $executer = [
        ROLE_NAME_REVIEW_COMMITTEE_MEMBER,
        ROLE_NAME_REVIEW_VICE_DIRECTOR,
        ROLE_NAME_REVIEW_DIRECTOR,
    ];
    private $currentRoundLogs;

    public function getNextStage()
    {
        return new Record($this->referrer);
    }

    public function renderFunctionArea()
    {
        if ($this->signable()) {
            return view('project/display/function/passsign')
                    ->with('title', $this->getStageName());
        }
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canPassSignStageUp($para = null)
    {
        if ('approve' == $para['operation']) {
            $approveCount = $this->getCurrentRoundLogs()->where('data1', 'approve')->count();
            $memberCount = SystemRole::whereIn('roleID', [
                    ROLE_ID_REVIEW_COMMITTEE_MEMBER,
                    ROLE_ID_REVIEW_VICE_DIRECTOR,
                    ROLE_ID_REVIEW_DIRECTOR
                ])->count();

            return $approveCount ==  $memberCount-1;
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

    public function signable()
    {
        $userLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $userRoleID = LoginUserKeeper::getUser()->getActiveRole()->roleID;

        return $this->getCurrentRoundLogs()
                    ->where('lanID', $userLanID)
                    ->whereLoose('roleID', $userRoleID)
                    ->isEmpty();
    }

    public function renderResult()
    {
        $fullCommittee = SystemRole::with('user', 'department')
            ->whereIn('roleID', [
                ROLE_ID_REVIEW_COMMITTEE_MEMBER,
                ROLE_ID_REVIEW_VICE_DIRECTOR,
                ROLE_ID_REVIEW_DIRECTOR
            ])->get();

        $signs = $this->getCurrentRoundLogs();

        return view('project/display/stage/passsignresult')
            ->with('signs', $signs)
            ->with('fullCommittee', $fullCommittee)
            ->with('passSignValues', Config::get('constants.passSignValues'));
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
        $log->roleID = LoginUserKeeper::getUser()->getActiveRole()->roleID;

        return $log;
    }

    public function getCurrentRoundLogs()
    {
        if (is_null($this->currentRoundLogs)) {
            //get last submit
            $lastSumbitRecord = $this->referrer->log()->where([
                ['fromStage', '=', STAGE_ID_SELECT_MODE],
                ['toStage', '>', STAGE_ID_SELECT_MODE]
            ])->orderBy('id', 'DESC')->first();

            if (is_null($lastSumbitRecord)) {
                $this->currentRoundLogs = collect([]);
            } else {
                $this->currentRoundLogs = $this->currentRoundLogs = $this->referrer->log()->with('operator')->where([
                    ['fromStage', '=', $this->getStageID()],
                    ['id', '>', $lastSumbitRecord->id]
                ])->get();
            }
        }

        return $this->currentRoundLogs;
    }
}