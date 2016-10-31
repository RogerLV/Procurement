<?php

namespace App\Logic\LoginUser;

use App\Exceptions\AppException;

class LoginUser
{
    private $userInfo;
    private $deptInfo;
    private $activeRole;

    public function __construct($lanID)
    {
        $this->userInfo = User::where('lanID', $lanID)->first();
        $this->activeRole = SystemRole::where('lanID' ,$lanID)->where('active', true)->first();
        $this->deptInfo = $this->getDepartmentInfo();
    }

    public function getUserInfo()
    {
        return $this->userInfo;
    }

    public function getDepartmentInfo()
    {
        if (is_null($this->deptInfo))
        {
            $dept = $this->getActiveRole()->dept;
            $this->deptInfo = Department::where('dept', $dept)->first();
        }

        return $this->deptInfo;
    }

    public function getActiveRole()
    {
        if (is_null($this->activeRole)){
            $this->activeRole = RoleHandler::initActiveRole($this->getUserInfo());
        }

        return $this->activeRole;
    }

    public function setActiveRole($id)
    {
        if (!RoleHandler::activable($this->getUserInfo(), $id))
        {
            throw new AppException('The specified role cannot be activated.');
        }

        $this->activeRole = RoleHandler::setActiveRole($this->getUserInfo(), $id);
    }
}