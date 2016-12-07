<?php

namespace App\Logic\Stage\ReviewMeetingStages;


use App\Models\ReviewMeeting;
use Gate;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Exceptions\AppException;

class StageHandler
{
    public static function renderReviewMeetingStageView(ReviewMeeting $reviewMeeting)
    {
        $stage = new Initiate($reviewMeeting);

        $infoView = "";
        while ($reviewMeeting->stage != $stage->getStageID() && !is_null($stage)) {
            $infoView .= $stage->renderInfoArea();
            $stage = $stage->getNextStage();
        }

        $functionView = "";
        if (Gate::forUser(LoginUserKeeper::getUser())->allows('review-meeting-operable', $reviewMeeting)) {
            $functionView = $stage->renderFunctionArea();
        }

        return $infoView . $functionView;
    }

    public static function getReviewMeetingStageIns(ReviewMeeting $reviewMeeting)
    {
        switch ($reviewMeeting->stage) {
            case STAGE_ID_REVIEW_MEETING_INITIATE: return new Initiate($reviewMeeting);
            case STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM: return new MemberConfirm($reviewMeeting);
            case STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES: return new GenerateMinutes($reviewMeeting);
            case STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS: return new MemberComments($reviewMeeting);
            case STAGE_ID_REVIEW_MEETING_SECRETARIAT_LEADER_APPROVE: return new SecretariatLeaderApprove($reviewMeeting);
            case STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE: return new DirectorApprove($reviewMeeting);
            case STAGE_ID_REVIEW_MEETING_DECIDE_PROCUREMENT_MODE: return new DecideProcurementMode($reviewMeeting);
            case STAGE_ID_REVIEW_MEETING_COMPLETE: return new Complete($reviewMeeting);

            default:
                throw new AppException('STGHDL002');
        }
    }
}