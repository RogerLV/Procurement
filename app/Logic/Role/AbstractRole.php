<?php

namespace App\Logic\Role;

use App\Logic\PageFactory;

abstract class AbstractRole
{
    protected $roleID;
    protected $roleName;
    protected $operable = true;
    protected $displayable = true;
    protected $assignDept = false;
    protected $accessiblePages = [
        ROUTE_NAME_ROLE_LIST,
    ];

    abstract public function getCandidates();

    public function getRoleID()
    {
        return $this->roleID;
    }

    public function getRoleName()
    {
        return $this->roleName;
    }

    public function canOperate()
    {
        return $this->operable;
    }

    public function assignDept()
    {
        return $this->assignDept;
    }

    public function getAccessiblePages()
    {
        $pageList = [];
        foreach ($this->accessiblePages as $routeName) {
            $pageList[] = PageFactory::create($routeName);
        }

        return $pageList;
    }

    public function canDisplay()
    {
        return $this->displayable;
    }
}