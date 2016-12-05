<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\Stage\ISimpleApprove;
use App\Logic\Stage\ReviewMeetingStage;
use App\Logic\Stage\TApprove;

class MemberConfirm extends ReviewMeetingStage implements ISimpleApprove
{
    use TApprove;

    protected $stageID = STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM;

    public function getNextStage()
    {
        return new GenerateMinutes($this->referrer);
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function getPreviousStage()
    {
        return null;
    }
}