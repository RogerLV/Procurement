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
        return view('review.display.function.generateminutes')
                ->with('title', $this->getStageName())
                ->with('reviewIns', $this->referrer)
                ->with('metaInfo', $this->referrer->metaInfo)
                ->with('topics', $this->referrer->topics()->with('topicable', 'meetingMinutesContent')->get());
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function canStageUp()
    {
        $emptyTopics = $this->referrer->topics()->with('meetingMinutesContent')->get()
                        ->filter(function ($ins, $key) {
                            return empty($ins->meetingMinutesContent);
                        });
        return !empty($this->referrer->metaInfo) && $emptyTopics->isEmpty();
    }

    public function operate($para = null)
    {
        // TODO: generate pdf and save;


        $this->logOperation();
    }
}