<?php

namespace App\Logic\System;

use App\Models\Footprint;
use App\Models\User;

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
}