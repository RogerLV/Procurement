<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ReviewMeetingStage;
use Config;
use App\Models\StageLog;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\Stage\ProjectStages\StageHandler as ProjectStageHandler;

class DecideProcurementMode extends ReviewMeetingStage implements IComplexOperation
{
    protected $stageID = STAGE_ID_REVIEW_MEETING_DECIDE_PROCUREMENT_MODE;
    protected $executer = [
        ROLE_NAME_SECRETARIAT
    ];

    public function getNextStage()
    {
        return new Complete($this->referrer);
    }

    public function renderFunctionArea()
    {
        return view('review.display.function.decideprocurementmode')
                ->with('title', $this->getStageName())
                ->with('topics', $this->getPendingProjects())
                ->with('procurementMethods', Config::get('constants.procurementMethods'));
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function operate($para = null)
    {
        $projectPara = [
            'operation' => $para['operation'],
            'comment' => $para['comment']
        ];

        $projectStage = ProjectStageHandler::getProjectStageIns($para['projectIns']);
        $projectStage->logFromReviewMeeting($projectPara);

        // log review meeting stage if all projects are settled
        if ($this->canStageUp()) {
            $this->logOperation();
        }
    }

    public function canStageUp()
    {
        return $this->getPendingProjects()->isEmpty();
    }

    public function logOperation($comment = null)
    {
        $log = new StageLog();
        $log->fromStage = $this->stageID;
        $log->toStage = $this->stageID;
        $log->dept = LoginUserKeeper::getUser()->getActiveRole()->dept;
        $log->lanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        $log->comment = $comment;
        $log->timeAt = date('Y-m-d H:i:s');
        $log->toStage = $this->getNextStage()->getStageID();

        $this->referrer->stage = $log->toStage;
        $this->referrer->save();

        $this->referrer->log()->save($log);
    }


    private function getPendingProjects()
    {
        return $this->referrer->topics()->with('topicable')->where('type', 'discussion')->get()
                ->filter(function ($ins) {
                    return $ins->topicable->stage == STAGE_ID_PASS_SIGN;
                });

    }
}