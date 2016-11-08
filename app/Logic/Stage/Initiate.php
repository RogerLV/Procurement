<?php

namespace App\Logic\Stage;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Project;
use App\Models\UpdateLog as Log;

class Initiate extends AbstractStage
{
    protected $stageID = STAGE_ID_INITIATE;

    public function __construct()
    {

    }

    public function operate($paras)
    {
        // create new project
        $this->project = new Project();
        $loginUser = LoginUserKeeper::getUser();

        $this->project->year = date('Y');
        $this->project->lanID = $loginUser->getUserInfo()->lanID;
        $this->project->dept = $loginUser->getDepartmentInfo()->dept;
        $this->project->scope = $paras['procurementScope'];
        $this->project->name = $paras['projectName'];
        $this->project->background = $paras['projectBackground'];
        $this->project->budget = $paras['projectBudget'];
        $this->project->involveReview = $paras['involveReview'];
        $this->project->save();

        Log::logInsert($this->project, $loginUser->getUserInfo()->lanID);
    }

    public function renderFunctionArea()
    {
        return null;
    }

    public function renderInfoArea()
    {
        return null;
    }

    public function getNextStage()
    {
        return new InviteDept();
    }
}