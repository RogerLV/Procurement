<?php

namespace App\Logic\Stage;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Project;
use App\Models\UpdateLog as Log;

class Initialization extends AbstractStage
{
    public function __construct($paras)
    {
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
}