<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRole extends Model
{
    public $table = 'ProjectRoles';

    protected $dates = ['deleted_at'];

    public function roleDepart()
    {
        return $this->belongsTo('App\Models\ProjectRoleDepartment', 'id', 'roleDeptID');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'lanID');
    }
}
