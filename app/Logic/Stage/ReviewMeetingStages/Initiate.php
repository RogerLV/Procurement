<?php

namespace App\Logic\Stage\ReviewMeetingStages;

use App\Logic\DepartmentKeeper;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ReviewMeetingStage;
use App\Logic\Stage\TLogOperation;
use Config;

class Initiate extends ReviewMeetingStage implements IComplexOperation
{
    use TLogOperation;

    protected $stageID = STAGE_ID_REVIEW_MEETING_INITIATE;

    public function getNextStage()
    {
        return new MemberConfirm($this->referrer);
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        $participants = $this->referrer->participants()->with('user')->get();

        return view('review/display/info/initiate')
            ->with('reviewIns', $this->referrer)
            ->with('stageNames', Config::get('constants.stageNames'))
            ->with('members', $participants->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER))
            ->with('specialInvitees', $participants->where('roleID', ROLE_ID_SPECIAL_INVITE))
            ->with('deptInfo', DepartmentKeeper::getDeptInfo())
            ->with('topics', $this->referrer->topics()->with('topicable')->get())
            ->with('topicTypeNames', Config::get('constants.TopicTypeNames'))
            ->with('loginUserRoleID', LoginUserKeeper::getUser()->getActiveRole()->roleID);
    }

    public function canStageUp()
    {
        if (is_null($this->referrer->date) || is_null($this->referrer->time) || is_null($this->referrer->venue)) {
            return false;
        }

        // check topics
        if (0 == $this->referrer->topics->count()) {
            return false;
        }

        // check committee invited
        return 0 != $this->referrer->participants->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)->count();
    }
}