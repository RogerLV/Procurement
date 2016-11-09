<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRoleDepartment extends Model
{
    protected $table = 'ProjectRoleDepartments';

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'dept', 'dept');
    }
}
