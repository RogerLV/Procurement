<?php

namespace App\Logic\LoginUser;


use App\Exceptions\AppException;
use App\Logic\System\Util;

class LoginUserKeeper
{
    private static $loginUser;

    public static function setUser($lanID)
    {
        if (!is_null($lanID)) {
            $lanID = strtoupper(base64_decode($lanID));
            session(['loginUser' => new LoginUser($lanID)]);
        }

        return self::getUser();
    }

    public static function getUser()
    {
        if (!(self::$loginUser instanceof LoginUser)) {
            self::getInstanceFromSession();
        }

        return self::$loginUser;
    }

    // The function will be run once and  only once during handling request
    // so that here is the idea place for recording footprints.
    private static function getInstanceFromSession()
    {
        // empty session check
        if (is_null($sessionUser = session('loginUser'))){
            throw new AppException(ERROR_MESSAGE_SESSION_TIMEOUT);
        }

        // check if login user roleID is still valid
        if (!RoleHandler::isActive($sessionUser->getActiveRole())) {
            throw new AppException(ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        // record footprints
        Util::recordFootprint($sessionUser->getUserInfo());

        self::$loginUser = $sessionUser;
    }
}