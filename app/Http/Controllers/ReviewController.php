<?php

namespace App\Http\Controllers;

use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Project;
use App\Models\ReviewMeeting;
use Config;
use Gate;
use App\Exceptions\AppException;

class ReviewController extends Controller
{
    public function apply($id = null)
    {
        if (is_null($id)) {
            $reviewMeetingIns = new ReviewMeeting();
            $reviewMeetingIns->stage = STAGE_ID_REVIEW_MEETING_INITIATE;
            $reviewMeetingIns->year = date('Y');
            $reviewMeetingIns->lanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;
        } else {
            $reviewMeetingIns = ReviewMeeting::getIns($id);
        }

        if (Gate::forUser(LoginUserKeeper::getUser())->denies('review-meeting-operable', $reviewMeetingIns)) {
            throw new AppException('RVWCTL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $reviewMeetingIns->save();

        $selectModeOptions = Project::with('log')->where('Projects.stage', STAGE_ID_PASS_SIGN)
            ->get()->filter(function ($projectIns, $key) {
                return $projectIns->log->where('data1', 'reject')->count() != 0;
            });
        $topics = $reviewMeetingIns->topics()->with('topicable')->get();

        return view('review.apply')
                ->with('title', PAGE_NAME_REVIEW_APPLY)
                ->with('reviewMeetingIns', $reviewMeetingIns)
                ->with('reviewOptions', Project::where('stage', STAGE_ID_REVIEW)->get())
                ->with('selectModeOptions', $selectModeOptions)
                ->with('topics', $topics)
                ->with('topicTypeNames', Config::get('constants.TopicTypeNames'));
    }

    public function edit()
    {
        if (empty($reviewMeetingID = trim(request()->input('reviewMeetingID')))
            || empty($attr = trim(request()->input('attr')))) {
            throw new AppException('RVWCTL002');
        }

        if (empty($value = trim(request()->input('value')))) {
            return response()->json(['status'=>'good']);
        }

        $reviewMeetingIns = ReviewMeeting::getIns($reviewMeetingID);
        if (STAGE_ID_REVIEW_MEETING_INITIATE != $reviewMeetingIns->stage) {
            throw new AppException('RVWCTL003', 'Incorrect Review Meeting Info');
        }

        if (Gate::forUser(LoginUserKeeper::getUser())->denies('review-meeting-operable', $reviewMeetingIns)) {
            throw new AppException('RVWCTL004', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        if ($value != $reviewMeetingIns->$attr) {
            $reviewMeetingIns->$attr = $value;
            $reviewMeetingIns->save();
        }

        return response()->json(['status'=>'good']);
    }
}
