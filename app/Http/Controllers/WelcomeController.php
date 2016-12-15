<?php

namespace App\Http\Controllers;

use App\Logic\DepartmentKeeper;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\LoginUser\RoleHandler;
use App\Logic\Role\RoleFactory;
use App\Models\Project;
use App\Models\ReviewMeeting;

class WelcomeController extends Controller
{
    public function __construct()
    {
        // As of start of the application, override parent
        // constructor preventing user initialization.
    }

    public function index()
    {
        $loginUser = LoginUserKeeper::initUser();
        $userRoleIns = RoleFactory::create($loginUser->getActiveRole()->roleID);
        $userRoles = RoleHandler::getAllRoles($loginUser->getUserInfo());

        $pendingProjects = Project::all()->filter(function ($ins) use ($userRoleIns) {
            return $userRoleIns->projectOperable($ins);
        });

        $pendingReviewMeetingsParticipate = ReviewMeeting::where([['date', '>=', date('Y-m-d')]])->whereIn('stage', [
            STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM,
            STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES
        ])->get()->filter(function ($ins) use ($userRoleIns) {
            return $userRoleIns->pendingReviewMeetingParticipate($ins);
        });

        $pendingReviewMeetingsProcess = ReviewMeeting::where(
            'stage',
            '<>',
            STAGE_ID_REVIEW_MEETING_COMPLETE
        )->get()->filter(function ($ins) use ($userRoleIns) {
            return $userRoleIns->reviewMeetingOperable($ins);
        });

        return view('welcome')
            ->with('userInfo', $loginUser->getUserInfo())
            ->with('userDeptInfo', $loginUser->getDepartmentInfo())
            ->with('pages', $userRoleIns->getAccessiblePages())
            ->with('userRoles', $userRoles)
            ->with('selectedMapID', $loginUser->getActiveRole()->id)
            ->with('deptInfo', DepartmentKeeper::getDeptInfo())
            ->with('pendingProjects', $pendingProjects)
            ->with('pendingReviewsMeetingsParticipate', $pendingReviewMeetingsParticipate)
            ->with('pendingReviewMeetingsProcess', $pendingReviewMeetingsProcess);
    }
}
