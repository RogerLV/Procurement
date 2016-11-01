<?php

namespace App\Http\Controllers;

use App\Logic\LoginUser\LoginUserKeeper;

class WelcomeController extends Controller
{
    public function index()
    {
        $loginUser = LoginUserKeeper::initUser();

        return view('welcome')
            ->with('userInfo', $loginUser->getUserInfo())
            ->with('deptInfo', $loginUser->getDepartmentInfo());
    }
}
