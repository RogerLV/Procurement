<?php

namespace App\Models;

use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\Model;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\UpdateLog AS Log;
use Config;

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

    public function vendors()
    {
        return $this->belongsToMany('App\Models\Vendor', 'ProjectVendorMapping', 'projectID', 'vendorID');
    }

    public function memberDepts()
    {
        return $this->hasMany('App\Models\ProjectRoleDepartment', 'projectID', 'id');
    }

    public function scoreItems()
    {
        return $this->hasMany('App\Models\ScoreItem', 'projectID', 'id');
    }

    public function negotiations()
    {
        return $this->hasMany('App\Models\Negotiation', 'projectID', 'id');
    }

    public function scores()
    {
        return $this->hasManyThrough(
            'App\Models\Score',
            'App\Models\ScoreItem',
            'projectID',
            'itemID',
            'id'
        );
    }

    public function roles()
    {
        return $this->hasManyThrough(
            'App\Models\ProjectRole',
            'App\Models\ProjectRoleDepartment',
            'projectID',
            'roleDeptID',
            'id'
        );
    }

    public function duediligence()
    {
        return $this->hasMany('App\Models\DueDiligence', 'projectID', 'id');
    }

    public function getProcurement()
    {
        return empty($this->approach)
            ? PROCUREMENT_METHOD_NOT_SELECTED
            : Config::get('constants.procurementMethods.'.$this->approach);
    }

    public static function getIns($projectID)
    {
        $projectIns = Project::find($projectID);

        if (is_null($projectIns)) {
            throw new AppException('PRJMDL001', 'Data Error');
        }

        return $projectIns;
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
        $project->stage = STAGE_ID_INITIATE;
        $project->background = $paras['projectBackground'];
        $project->budget = $paras['projectBudget'];
        $project->involveReview = $paras['involveReview'] == 'true';
        $project->save();

        Log::logInsert($project);

        return $project;
    }
}
