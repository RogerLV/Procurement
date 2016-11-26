<?php

namespace App\Logic\Stage;


use App\Models\ReviewMeeting;

abstract class ReviewMeetingStage extends AbstractStage
{
    public function __construct(ReviewMeeting $reviewMeetingIns)
    {
        $this->referrer = $reviewMeetingIns;
    }

    public function getReviewMeeting()
    {
        return $this->referrer;
    }
}