<?php

namespace App\Http\Controllers;

use App\Logic\DepartmentKeeper;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Logic\LoginUser\RoleHandler;
use App\Logic\Role\RoleFactory;

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

        return view('welcome')
            ->with('userInfo', $loginUser->getUserInfo())
            ->with('userDeptInfo', $loginUser->getDepartmentInfo())
            ->with('pages', $userRoleIns->getAccessiblePages())
            ->with('userRoles', $userRoles)
            ->with('selectedMapID', $loginUser->getActiveRole()->id)
            ->with('deptInfo', DepartmentKeeper::getDeptInfo());
    }
}
