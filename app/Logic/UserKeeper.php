<?php

namespace App\Logic;


use App\Models\User;

class UserKeeper
{
    private static $userStack;
    private static $userInfoStack = [];

    public static function pushUser($users)
    {
        $users = is_array($users) ? $users : [$users];

        self::$userStack = array_unique(array_merge(self::$userStack, $users));
    }

    public static function getUsers()
    {
        if (!empty($newLanIDs = self::getNewLanIDs())) {
            $newUserInfo = User::whereIn('lanID', $newLanIDs)->get()->keyBy('lanID')->toArray();
            self::$userInfoStack = array_merge(self::$userInfoStack, $newUserInfo);
        }

        return collect(self::$userInfoStack);
    }

    private static function getNewLanIDs()
    {
        $newLanIDs = [];
        foreach (self::$userStack as $lanID) {
            if (!array_key_exists($lanID, self::$userInfoStack)) {
                $newLanIDs[] = $lanID;
            }
        }

        return $newLanIDs;
    }

}