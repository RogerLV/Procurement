<?php

namespace App\Logic\DocumentType\HyperDocument;


use App\Models\ReviewTopic;

class MeetingMinutesDoc extends HyperDocument
{
    protected $type = HYPER_DOC_NAME_MEETING_MINUTES;
    protected $name = HYPER_DOC_NAME_MEETING_MINUTES;

    public function __construct(ReviewTopic $reviewIns)
    {
        switch ($reviewIns->type) {
            case 'review':
                $this->name = HYPER_DOC_NAME_MEETING_MINUTES_REVIEW;
                break;

            case 'discussion':
                $this->name = HYPER_DOC_NAME_MEETING_MINUTES_DISCUSS;
                break;
        }

        $this->url = url('meetingMinutes/topic/'.$reviewIns->id);

        // set uploader and timeAt from review meeting generate meeting minutes stage log info
        $log = $reviewIns->reviewMeeting->log()->with('operator')->where([
            ['fromStage', '=', STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES],
            ['toStage', '>', STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES]
        ])->orderBy('id', 'desc')->first();

        if (!is_null($log)) {
            $this->uploader = $log->operator->getTriName();
            $this->timeAt = $log->timeAt;
        }
    }
}