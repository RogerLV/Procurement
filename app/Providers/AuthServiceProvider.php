<?php

namespace App\Providers;

use App\Logic\LoginUser\LoginUser;
use App\Logic\LoginUser\LoginUserKeeper;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
            $loginUser = LoginUserKeeper::getUser();
            return $loginUser->getActiveRole()->roleID == ROLE_ID_DEPT_MAKER;
        });

        $this->registerPolicies($gate);
    }
}
