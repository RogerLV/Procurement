<?php

namespace App\Http\Controllers;

use App\Logic\DepartmentKeeper;
use App\Models\Project;
use App\Models\ReviewMeeting;
use App\Models\SystemRole;
use Config;
use Gate;
use App\Exceptions\AppException;
use App\Logic\Stage\ReviewMeetingStages\StageHandler as ReviewMeetingStageHandler;

class ReviewController extends Controller
{
    public function apply($id = null)
    {
        if (is_null($id)) {
            $reviewMeetingIns = new ReviewMeeting();
            $reviewMeetingIns->stage = STAGE_ID_REVIEW_MEETING_INITIATE;
            $reviewMeetingIns->year = date('Y');
            $reviewMeetingIns->lanID = $this->loginUser->getUserInfo()->lanID;
        } else {
            $reviewMeetingIns = ReviewMeeting::getIns($id);
        }

        if (Gate::forUser($this->loginUser)->denies('review-meeting-operable', $reviewMeetingIns)) {
            throw new AppException('RVWCTL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $reviewMeetingIns->save();

        $selectModeOptions = Project::with('log')->where('Projects.stage', STAGE_ID_PASS_SIGN)
            ->get()->filter(function ($projectIns, $key) {
                return $projectIns->log->where('data1', 'reject')->count() != 0;
            });
        $topics = $reviewMeetingIns->topics()->with('topicable')->get();
        $committee = SystemRole::with('user')->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)->get();
        $specialInvite = SystemRole::with('user')->where('roleID', ROLE_ID_SPECIAL_INVITE)->get();

        return view('review.stage.apply')
                ->with('title', PAGE_NAME_REVIEW_APPLY)
                ->with('reviewMeetingIns', $reviewMeetingIns)
                ->with('reviewOptions', Project::where('stage', STAGE_ID_REVIEW)->get())
                ->with('selectModeOptions', $selectModeOptions)
                ->with('topics', $topics)
                ->with('topicTypeNames', Config::get('constants.TopicTypeNames'))
                ->with('committee', $committee)
                ->with('specialInvites', $specialInvite)
                ->with('invited', $reviewMeetingIns->participants)
                ->with('deptInfo', DepartmentKeeper::getDeptInfo());
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

        if (Gate::forUser($this->loginUser)->denies('review-meeting-operable', $reviewMeetingIns)) {
            throw new AppException('RVWCTL004', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        if ($value != $reviewMeetingIns->$attr) {
            $reviewMeetingIns->$attr = $value;
            $reviewMeetingIns->save();
        }

        return response()->json(['status'=>'good']);
    }

    public function display($id)
    {
        $reviewMeetingIns = ReviewMeeting::getIns($id);
        $stageView = ReviewMeetingStageHandler::renderReviewMeetingStageView($reviewMeetingIns);
        $logs = $reviewMeetingIns->log()->with('operator')->get();

        return view('review.display')
                ->with('reviewMeetingIns', $reviewMeetingIns)
                ->with('stageView', $stageView)
                ->with('logs', $logs)
                ->with('stageNames', Config::get('constants.stageNames'));
    }
}
