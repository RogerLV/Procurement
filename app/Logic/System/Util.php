<?php

namespace App\Logic\System;

use App\Models\Footprint;
use App\Models\User;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\Role\RoleFactory;

class Util
{
    public static function recordFootprint(User $loginUser)
    {
        $footprint = new Footprint;
        $footprint->lanID = $loginUser->lanID;
        $footprint->uri = request()->path();
        $footprint->app = env('APP_NAME');
        $footprint->save();
    }

    public static function checkRole($roleAry)
    {
        $loginUser = LoginUserKeeper::getUser();

        $roleAry = is_array($roleAry) ? $roleAry : [$roleAry];
        return in_array($loginUser->getActiveRole()->roleID, $roleAry);
    }
}