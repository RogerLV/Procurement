<?php

namespace App\Http\Controllers;


use App\Exceptions\AppException;
use App\Models\MeetingMinutesContent;
use App\Models\MeetingMinutesMetaInfo;
use App\Models\ReviewMeeting;
use App\Models\ReviewTopic;
use Gate;

class MeetingMinutesController extends Controller
{
    public function addMetaInfo()
    {
        if (empty($reviewMeetingID = trim(request()->input('reviewMeetingID')))
            || empty($header = trim(request()->input('header')))
            || empty($date = trim(request()->input('date')))
            || empty($time = trim(request()->input('time')))
            || empty($venue = trim(request()->input('venue')))
            || empty($host = trim(request()->input('host')))
            || empty($attendance = trim(request()->input('attendance')))
            || empty($recorder = trim(request()->input('recorder')))
            || empty($sendTo = trim(request()->input('sendTo')))
            || empty($footer = trim(request()->input('footer')))) {
            throw new AppException('MMCTL001');
        }

        $reviewMeetingIns = ReviewMeeting::getIns($reviewMeetingID);

        if (Gate::forUser($this->loginUser)->denies('generate-meeting-minutes', $reviewMeetingIns)) {
            throw new AppException('MMCTL002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $metaInfo = $reviewMeetingIns->metaInfo;
        if (empty($metaInfo)) {
            $metaInfo = new MeetingMinutesMetaInfo();
        }

        $metaInfo->header = $header;
        $metaInfo->date = $date;
        $metaInfo->time = $time;
        $metaInfo->venue = $venue;
        $metaInfo->host = $host;
        $metaInfo->attendance = $attendance;
        $metaInfo->recorder = $recorder;
        $metaInfo->sendTo = $sendTo;
        $metaInfo->footer = $footer;
        $reviewMeetingIns->metaInfo()->save($metaInfo);

        return response()->json(['status'=>'good']);
    }

    public function addContent()
    {
        if (empty($topicID = trim(request()->input('topicID')))
            || empty($content = rtrim(request()->input('content')))) {
            throw new AppException('MMCTL003');
        }

        $topicIns = ReviewTopic::getIns($topicID);

        if (Gate::forUser($this->loginUser)->denies('generate-meeting-minutes', $topicIns->reviewMeeting)) {
            throw new AppException('MMCTL004', ERROR_MESSAGE_NOT_AUTHORIZED);
        }
        $meetingMinutesContent = $topicIns->meetingMinutesContent;
        if (empty($meetingMinutesContent)) {
            $meetingMinutesContent = new MeetingMinutesContent();
        }

        $meetingMinutesContent->content = nl2br(htmlentities($content));
        $topicIns->meetingMinutesContent()->save($meetingMinutesContent);

        return response()->json(['status'=>'good']);
    }
}
