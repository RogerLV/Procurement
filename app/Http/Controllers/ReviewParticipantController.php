<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Models\ReviewMeeting;
use App\Models\SystemRole;
use App\Models\ReviewParticipant;
use Gate;

class ReviewParticipantController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function edit()
    {
        if (empty($reviewMeetingID = trim(request()->input('reviewMeetingID')))
            || empty($lanID = trim(request()->input('lanID')))
            || empty($operation = trim(request()->input('operation')))
            || empty($roleID = trim(request()->input('roleID')))) {
            throw new AppException('PTCPCTL001');
        }

        $reviewMeetingIns = ReviewMeeting::getIns($reviewMeetingID);

        if (STAGE_ID_REVIEW_MEETING_INITIATE != $reviewMeetingIns->stage) {
            throw new AppException('PTCPCTL002', 'Incorrect Review Meeting Info');
        }

        if (Gate::forUser($this->loginUser)->denies('review-meeting-operable', $reviewMeetingIns)) {
            throw new AppException('PTCPCTL003', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $existence = SystemRole::where([
            ['roleID', '=', $roleID],
            ['lanID', '=', $lanID],
        ])->count();
        if (0 == $existence) {
            throw new AppException('PTCPCTL004', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        switch ($operation) {
            case 'add':
                $participant = new ReviewParticipant();
                $participant->lanID = $lanID;
                $participant->roleID = $roleID;
                $reviewMeetingIns->participants()->save($participant);
                break;

            case 'remove':
                $reviewMeetingIns->participants()->where('lanID', $lanID)->where('roleID', $roleID)->delete();
                break;
        }

        return response()->json(['status'=>'good']);
    }
}
