<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Logic\Stage\ISimpleApprove;
use App\Logic\Stage\ReviewMeetingStage;
use App\Logic\Stage\TApprove;

class DirectorApprove extends ReviewMeetingStage implements ISimpleApprove
{
    use TApprove;

    protected $stageID = STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE;
    protected $executer = [
        ROLE_NAME_REVIEW_DIRECTOR
    ];

    public function getNextStage()
    {
        if ($this->referrer->topics->where('type', 'discussion')->count() == 0) {
            return new Complete($this->referrer);
        } else {
            return new DecideProcurementMode($this->referrer);
        }
    }

    public function renderFunctionArea()
    {
        return view('review.display.function.directorapprove')
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

    public function operate($para = null)
    {
        // set review project stage to finish
        if ('approve' == $para['operation']) {
            $reviewTopics = $this->referrer->topics()->with('topicable')
                ->where('type', 'review')->get();

            foreach ($reviewTopics as $topic) {
                $topic->topicable->stage = STAGE_ID_FILE_CONTRACT;
                $topic->topicable->save();
            }
        }

        $this->approve($para['operation'], $para['comment']);
    }
}