<?php

namespace App\Http\Controllers;


use App\Exceptions\AppException;
use Gate;
use App\Models\ReviewMeeting;
use App\Logic\Stage\ReviewMeetingStages\StageHandler as ReviewMeetingStageHandler;

class ReviewStageController extends Controller
{
    protected $reviewMeetingIns;
    protected $stageIns;

    public function __construct()
    {
        parent::__construct();

        if (empty($reviewMeetingID = request()->input('reviewMeetingID'))) {
            throw new AppException('RVWSTGCTL001', 'Incorrect review meeting info.');
        }

        $this->reviewMeetingIns = ReviewMeeting::getIns($reviewMeetingID);

        if (Gate::forUser($this->loginUser)->denies('review-meeting-operable', $this->reviewMeetingIns)) {
            throw new AppException('RVWSTGCTL002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $this->stageIns = ReviewMeetingStageHandler::getReviewMeetingStageIns($this->reviewMeetingIns);
    }

    public function complete()
    {
        if (!$this->stageIns->canStageUp()) {
            throw new AppException('RVWSTGCTL003', 'Stage finish conditions are not met.');
        }

        $this->stageIns->operate(null);

        return response()->json(['status' => 'good']);
    }

    public function approve()
    {
        if (empty($para['operation'] = trim(request()->input('operation')))) {
            throw new AppException('RVWSTGCTL004');
        }

        if (!in_array($para['operation'], ['approve', 'reject'])) {
            throw new AppException('RVWSTGCTL005');
        }

        $para['comment'] = trim(request()->input('comment'));

        $this->stageIns->operate($para);

        return response()->json(['status' => 'good']);
    }
}
