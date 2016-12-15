<?php

namespace App\Providers;

use App\Logic\LoginUser\LoginUser;
use App\Logic\Role\RoleFactory;
use App\Logic\ScoreHandler;
use App\Models\Document;
use App\Models\Project;
use App\Models\ReviewMeeting;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {

        $gate->define('apply-project', function (LoginUser $loginUser) {
            return $loginUser->getActiveRole()->roleID == ROLE_ID_DEPT_MAKER;
        });

        $gate->define('project-removable', function (LoginUser $loginUser) {
            return $loginUser->getActiveRole()->roleID == ROLE_ID_APP_ADMIN;
        });

        $gate->define('project-visible', function (LoginUser $loginUser, Project $projectIns) {
            $roleIns = RoleFactory::create($loginUser->getActiveRole()->roleID);
            return $roleIns->projectVisible($projectIns);
        });

        $gate->define('project-operable', function (LoginUser $loginUser, Project $projectIns) {
            $roleIns = RoleFactory::create($loginUser->getActiveRole()->roleID);
            return $roleIns->projectOperable($projectIns);
        });

        $gate->define('document-visible', function (LoginUser $loginUser, Document $documentIns) {
            $referenceIns = $documentIns->documentable;
            if ($referenceIns instanceof Project) {
                if (in_array($referenceIns->stage, [STAGE_ID_DUE_DILIGENCE, STAGE_ID_REVIEW])
                    && $loginUser->getActiveRole()->roleID == ROLE_ID_DEPT_MAKER) {
                    return $documentIns->type != DOC_TYPE_DUE_DILIGENCE_REPORT;
                }
                return Gate::forUser($loginUser)->check('project-visible', $referenceIns);
            }
        });

        $gate->define('score-template-editable', function (LoginUser $loginUser, Project $projectIns) {
            return $projectIns->lanID == $loginUser->getUserInfo()->lanID
            && ScoreHandler::getPhase($projectIns) == 'ScoreStageEditTemplate';
        });

        $gate->define('scorable', function (LoginUser $loginUser, Project $projectIns) {
            return $projectIns->roles->pluck('lanID')->contains($loginUser->getUserInfo()->lanID)
                && ScoreHandler::getPhase($projectIns) == 'ScoreStageMemberScoring';
        });

        $gate->define('review-meeting-visible', function (LoginUser $loginUser, ReviewMeeting $reviewMeetingIns) {
            $roleIns = RoleFactory::create($loginUser->getActiveRole()->roleID);
            return $roleIns->reviewMeetingVisible($reviewMeetingIns);
        });

        $gate->define('review-meeting-operable', function (LoginUser $loginUser, ReviewMeeting $reviewMeetingIns) {
            $roleIns = RoleFactory::create($loginUser->getActiveRole()->roleID);
            return $roleIns->reviewMeetingOperable($reviewMeetingIns);
        });

        $gate->define('apply-review-meeting', function (LoginUser $loginUser) {
            return $loginUser->getActiveRole()->roleID == ROLE_ID_SECRETARIAT;
        });

        $gate->define('generate-meeting-minutes', function (LoginUser $loginUser, ReviewMeeting $reviewMeetingIns) {
            return $loginUser->getActiveRole()->roleID == ROLE_ID_SECRETARIAT
                    && $reviewMeetingIns->stage == STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES;
        });

        $this->registerPolicies($gate);
    }
}
