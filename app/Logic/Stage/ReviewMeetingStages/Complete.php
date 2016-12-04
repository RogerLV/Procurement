<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\Stage\ReviewMeetingStage;

class Complete extends ReviewMeetingStage
{
    protected $stageID = STAGE_ID_REVIEW_MEETING_COMPLETE;

    public function getNextStage()
    {
        return null;
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function operate($para = null)
    {
        return null;
    }
}