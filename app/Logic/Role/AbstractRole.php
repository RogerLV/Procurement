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
    protected $commonPages = [
        ROUTE_NAME_ROLE_LIST,
        ROUTE_NAME_PROJECT_LIST,
    ];
    protected $roleSpecPages = [];
    protected $operableStages= [];

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
        foreach ($this->roleSpecPages as $routeName) {
            $pageList[] = PageFactory::create($routeName);
        }

        foreach ($this->commonPages as $routeName) {
            $pageList[] = PageFactory::create($routeName);
        }

        return $pageList;
    }

    public function canDisplay()
    {
        return $this->displayable;
    }

    public function projectVisible($projectIns)
    {
        return true;
    }

    public function projectOperable($projectIns)
    {
        return in_array($projectIns->stage, $this->operableStages);
    }
}