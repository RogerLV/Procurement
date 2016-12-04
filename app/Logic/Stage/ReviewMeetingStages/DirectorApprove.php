<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\Stage\ISimpleApprove;
use App\Logic\Stage\ReviewMeetingStage;
use App\Logic\Stage\TApprove;

class DirectorApprove extends ReviewMeetingStage implements ISimpleApprove
{
    use TApprove;

    protected $stageID = STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE;

    public function getNextStage()
    {
        return new Complete($this->referrer);
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return null;
    }
}