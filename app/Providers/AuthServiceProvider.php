<?php

namespace App\Providers;

use App\Logic\LoginUser\LoginUser;
use App\Logic\Role\RoleFactory;
use App\Logic\ScoreHandler;
use App\Models\Document;
use App\Models\Project;
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

        $this->registerPolicies($gate);
    }
}
