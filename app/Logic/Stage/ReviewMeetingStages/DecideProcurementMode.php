<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ReviewMeetingStage;
use App\Logic\Stage\TLogOperation;

class DecideProcurementMode extends ReviewMeetingStage implements IComplexOperation
{
    use TLogOperation;
    protected $stageID = STAGE_ID_REVIEW_MEETING_DECIDE_PROCUREMENT_MODE;

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

    public function canStageUp()
    {
        // TODO: Implement canStageUp() method.
    }
}