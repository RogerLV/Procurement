<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Models\Project;
use App\Models\PutRecord;
use App\Models\ReviewMeeting;
use Gate;

class TopicController extends Controller
{
    private $reviewMeetingIns;

    public function __construct()
    {
        parent::__construct();

        if (empty($reviewMeetingID = trim(request()->input('reviewMeetingID')))) {
            throw new AppException('TPCCTL001');
        }

        $this->reviewMeetingIns = ReviewMeeting::getIns($reviewMeetingID);

        // operable check
        if (Gate::forUser($this->loginUser)->denies('review-meeting-operable', $this->reviewMeetingIns)) {
            throw new AppException('TPCCTL002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }
    }

    public function addProject()
    {
        // parameter check
        if (empty($type = trim(request()->input('type')))
            || empty($projectID = trim(request()->input('projectID')))) {
            throw new AppException('TPCCTL003');
        }

        // stage check
        if (STAGE_ID_REVIEW_MEETING_INITIATE != $this->reviewMeetingIns->stage) {
            throw new AppException('TPCCTL004', 'Incorrect Review Meeting Info');
        }

        // existence check
        $projectIns = Project::getIns($projectID);
        $topics = $this->reviewMeetingIns->topics()->with('topicable')->get();
        $existence = $topics->filter(function ($ins, $key) use ($projectIns, $type) {
            $referrerIns = $ins->topicable;
            return ($referrerIns instanceof Project)
                && ($referrerIns->id == $projectIns->id)
                && ($ins->type == $type);
        });

        if (0 != $existence->count()) {
            throw new AppException('TPCCTL005', 'Topic is already added.');
        }

        $topic = $this->reviewMeetingIns->topics()->create([]);
        $topic->type = $type;
        $projectIns->topics()->save($topic);

        return response()->json(['status'=>'good']);
    }

    public function addPutRecord()
    {
        // parameter check, stage check, operable check, existence
        if (empty($content = trim(request()->input('content')))
            || empty($type = trim(request()->input('type')))) {
            throw new AppException('TPCCTL006');
        }

        // stage check
        if (STAGE_ID_REVIEW_MEETING_INITIATE != $this->reviewMeetingIns->stage) {
            throw new AppException('TPCCTL007', 'Incorrect Review Meeting Info');
        }

        // create new put record and add relationship to the review meeting
        $putRecord = new PutRecord();
        $putRecord->content = $content;
        $putRecord->save();

        $topic = $this->reviewMeetingIns->topics()->create([]);
        $topic->type = $type;
        $putRecord->topics()->save($topic);

        return response()->json(['status'=>'good']);
    }
}
