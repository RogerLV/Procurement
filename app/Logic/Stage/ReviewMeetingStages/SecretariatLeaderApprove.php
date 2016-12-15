<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\Stage\ISimpleApprove;
use App\Logic\Stage\ReviewMeetingStage;
use App\Logic\Stage\TApprove;

class SecretariatLeaderApprove extends ReviewMeetingStage implements ISimpleApprove
{
    use TApprove;

    protected $stageID = STAGE_ID_REVIEW_MEETING_SECRETARIAT_LEADER_APPROVE;
    protected $executer = [
        ROLE_NAME_SECRETARIAT_LEADER
    ];

    public function getNextStage()
    {
        return new DirectorApprove($this->referrer);
    }

    public function renderFunctionArea()
    {
        return view('review.display.function.secretariatleaderapprove')
            ->with('title', $this->getStageName());
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function getPreviousStage()
    {
        return new GenerateMinutes($this->referrer);
    }
}