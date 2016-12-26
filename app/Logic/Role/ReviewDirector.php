<?php

namespace App\Logic\Role;


use App\Models\ReviewMeeting;
use App\Models\Project;
use App\Logic\Stage\ProjectStages\StageHandler as ProjectStageHandler;
use App\Logic\Stage\ReviewMeetingStages\StageHandler as ReviewMeetingStageHandler;

class ReviewDirector extends AbstractRole
{
    protected $roleID = 8;
    protected $roleName = ROLE_NAME_REVIEW_DIRECTOR;
    protected $operable = false;
    protected $operableStages = [
        STAGE_ID_PASS_SIGN,
        STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM,
        STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS,
        STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE
    ];
    protected $roleSpecPages = [
        'ReviewMeetingList'
    ];

    public function getCandidates()
    {
        return [];
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
                    return !$this->reviewMeetingStageOperated($reviewMeetingIns);

                default:
                    return true;
            }
        }

        return false;
    }

    public function pendingReviewMeetingParticipate(ReviewMeeting $reviewMeeting)
    {
        return $reviewMeeting->date >= date('Y-m-d')
        && in_array($reviewMeeting->stage, $this->stages['reviewMeetingPendingParticipate']);
    }


    private function reviewMeetingStageOperated(ReviewMeeting $reviewMeeting)
    {
        return ReviewMeetingStageHandler::getReviewMeetingStageIns($reviewMeeting)->operated();
    }
}