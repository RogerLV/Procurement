<?php

namespace App\Logic\Stage\ProjectStages;


use App\Logic\MeetingMinutesHandler;
use App\Logic\Stage\ProjectStage;

class Review extends ProjectStage
{
    protected $stageID = STAGE_ID_REVIEW;

    public function getNextStage()
    {
        return new FileContract($this->referrer);
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        $topicIns = $this->referrer->topics()->with(
            'meetingMinutesContent',
            'topicable',
            'reviewMeeting.log.operator'
        )->where('type', 'review')->first();

        return MeetingMinutesHandler::renderTopic($topicIns);
    }

    public function operate($para = null)
    {
        return null;
    }
}