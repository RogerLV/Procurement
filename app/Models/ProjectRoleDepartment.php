<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectRoleDepartment extends Model
{
    use SoftDeletes;

    public $table = 'ProjectRoleDepartments';
    protected $dates = ['deleted_at'];

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'dept', 'dept');
    }

    public function role()
    {
        return $this->hasMany('App\Models\ProjectRole', 'roleDeptID', 'id');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'id', 'projectID');
    }

    public static function getIns($memberDeptID)
    {
        $memberDeptIns = self::find($memberDeptID);

        if (is_null($memberDeptIns)) {
            throw new AppException('MBRDEPTMDL001');
        }

        return $memberDeptIns;
    }
}
