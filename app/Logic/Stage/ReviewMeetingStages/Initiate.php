<?php

namespace App\Logic\Stage\ReviewMeetingStages;

use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ReviewMeetingStage;
use App\Logic\Stage\TLogOperation;

class Initiate extends ReviewMeetingStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_REVIEW_MEETING_INITIATE;

    public function getNextStage()
    {
        return new MemberConfirm($this->referrer);
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
        if (is_null($this->referrer->date) || is_null($this->referrer->time) || is_null($this->referrer->venue)) {
            return false;
        }

        // check topics
        if (0 == $this->referrer->topics->count()) {
            return false;
        }

        // check committee invited
        return 0 != $this->referrer->participants->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)->count();
    }
}