<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRoleDepartment extends Model
{
    public $table = 'ProjectRoleDepartments';

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'dept', 'dept');
    }

    public function role()
    {
        return $this->hasMany('App\Models\ProjectRole', 'roleDeptID', 'id');
    }
}
