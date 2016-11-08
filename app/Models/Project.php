<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\UpdateLog AS Log;

class Project extends Model
{
    public $table = 'Projects';

    public function conversation()
    {
        return $this->morphMany('App\Models\Conversation', 'conversable');
    }

    public function document()
    {
        return $this->morphMany('App\Models\Document', 'documentable');
    }

    public function log()
    {
        return $this->morphMany('App\Models\ProjectStageLog', 'logable');
    }

    public function submitter()
    {
        return $this->hasOne('App\Models\User', 'lanID' ,'lanID');
    }

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'dept', 'dept');
    }

    public static function createNew($paras)
    {
        // create new project
        $project = new Project();
        $loginUser = LoginUserKeeper::getUser();

        $project->year = date('Y');
        $project->lanID = $loginUser->getUserInfo()->lanID;
        $project->dept = $loginUser->getDepartmentInfo()->dept;
        $project->scope = $paras['procurementScope'];
        $project->name = $paras['projectName'];
        $project->stage = STAGE_ID_INVITE_DEPT;
        $project->background = $paras['projectBackground'];
        $project->budget = $paras['projectBudget'];
        $project->involveReview = $paras['involveReview'];
        $project->save();

        Log::logInsert($project, $loginUser->getUserInfo()->lanID);

        return $project;
    }
}
