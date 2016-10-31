<?php

namespace App\Logic\LoginUser;

use App\Models\SystemRole;
use App\Models\User;

class RoleHandler
{
    public static function setActiveRole(User $userInfo, $newMapID)
    {
        // first set original role inactive
        SystemRole::where('lanID', $userInfo->lanID)
            ->where('active', true)
            ->update(['active' => false]);

        // then set new one active
        $roleIns = SystemRole::find($newMapID);
        $roleIns->active = true;
        $roleIns->save();

        return $roleIns;
    }

    public static function initActiveRole(User $userInfo)
    {
        // find highest roleID map
        $ins = SystemRole::where('lanID', $userInfo->lanID)
                ->orderBy('roleID', 'DESC')
                ->first();

        self::setActiveRole($userInfo, $ins->id);

        return $ins;
    }

    public static function isActive(SystemRole $sessionRoleIns)
    {
        $DBins = SystemRole::find($sessionRoleIns->id);
        return !is_null($DBins) && $DBins->active;
    }

    public static function activable($userInfo, $newMapID)
    {
        $roleIns = SystemRole::find($newMapID);
        return !is_null($roleIns) && $roleIns->lanID == $userInfo->lanID && !$roleIns->active;
    }
}