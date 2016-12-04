<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ReviewMeetingStage;
use App\Logic\Stage\TLogOperation;

class GenerateMinutes extends ReviewMeetingStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES;

    public function getNextStage()
    {
        return new MemberComments($this->referrer);
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        return false;
    }
}