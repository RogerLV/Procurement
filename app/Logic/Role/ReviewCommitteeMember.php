<?php

namespace App\Logic\Role;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\User;
use App\Models\ReviewMeeting;
use App\Models\Project;
use App\Logic\Stage\ReviewMeetingStages\StageHandler as ReviewMeetingStageHandler;
use App\Logic\Stage\ProjectStages\StageHandler as ProjectStageHandler;

class ReviewCommitteeMember extends AbstractRole
{
    protected $roleID = 6;
    protected $roleName = ROLE_NAME_REVIEW_COMMITTEE_MEMBER;
    protected $operableStages= [
        STAGE_ID_PASS_SIGN,
        STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM,
        STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS,
    ];
    protected $roleSpecPages = [
        'ReviewMeetingList'
    ];

    public function getCandidates()
    {
        return User::inService()->orderBy('uEngName')->get();
    }

    public function projectOperable(Project $projectIns)
    {
        if (parent::projectOperable($projectIns)) {
            switch($projectIns->stage) {
                case STAGE_ID_PASS_SIGN:
                    $stageIns = ProjectStageHandler::getProjectStageIns($projectIns);
                    return $stageIns->signable();
            }
        }

        return false;
    }

    public function reviewMeetingVisible(ReviewMeeting $reviewMeeting)
    {
        return STAGE_ID_REVIEW_MEETING_INITIATE != $reviewMeeting->stage;
    }

    public function reviewMeetingOperable(ReviewMeeting $reviewMeetingIns)
    {
        if (parent::reviewMeetingOperable($reviewMeetingIns)) {
            switch ($reviewMeetingIns->stage) {
                case STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM:
                case STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS:
                    return $this->reviewMeetingInvited($reviewMeetingIns)
                            && !$this->reviewMeetingStageOperated($reviewMeetingIns);

                default:
                    return false;
            }
        }

        return false;
    }

    public function pendingReviewMeetingParticipate(ReviewMeeting $reviewMeeting)
    {
        // check invited at last reducing chance to run query
        return $reviewMeeting->date >= date('Y-m-d')
                && in_array($reviewMeeting->stage, $this->stages['reviewMeetingPendingParticipate'])
                && $this->reviewMeetingInvited($reviewMeeting);
    }


    private function reviewMeetingInvited(ReviewMeeting $reviewMeeting)
    {
        $loginUserLanID = LoginUserKeeper::getUser()->getUserInfo()->lanID;

        return !is_null(
            $reviewMeeting->participants()->where([
                ['roleID', '=', ROLE_ID_REVIEW_COMMITTEE_MEMBER],
                ['lanID', '=', $loginUserLanID]
            ])->first()
        );
    }

    private function reviewMeetingStageOperated(ReviewMeeting $reviewMeeting)
    {
        return ReviewMeetingStageHandler::getReviewMeetingStageIns($reviewMeeting)->operated();
    }
}