<?php

namespace App\Logic\Stage\ReviewMeetingStages;

use App\Logic\DepartmentKeeper;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\Stage\IComplexOperation;
use App\Logic\Stage\ReviewMeetingStage;
use App\Logic\Stage\TLogOperation;
use Config;
use App\Models\Project;
use App\Models\SystemRole;

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
        $selectModeOptions = Project::with('log')->where('Projects.stage', STAGE_ID_PASS_SIGN)
            ->get()->filter(function ($projectIns, $key) {
                return $projectIns->log->where('data1', 'reject')->count() != 0;
            });
        $topics = $this->referrer->topics()->with('topicable')->get();
        $committee = SystemRole::with('user')->where('roleID', ROLE_ID_REVIEW_COMMITTEE_MEMBER)->get();
        $specialInvite = SystemRole::with('user')->where('roleID', ROLE_ID_SPECIAL_INVITE)->get();

        return view('review.display.function.initiate')
                ->with('title', PAGE_NAME_REVIEW_APPLY)
                ->with('reviewMeetingIns', $this->referrer)
                ->with('reviewOptions', Project::where('stage', STAGE_ID_REVIEW)->get())
                ->with('selectModeOptions', $selectModeOptions)
                ->with('topics', $topics)
                ->with('topicTypeNames', Config::get('constants.TopicTypeNames'))
                ->with('committee', $committee)
                ->with('specialInvites', $specialInvite)
                ->with('invited', $this->referrer->participants)
                ->with('deptInfo', DepartmentKeeper::getDeptInfo());
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