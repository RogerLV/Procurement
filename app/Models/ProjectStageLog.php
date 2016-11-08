<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStageLog extends Model
{
    protected $table = 'ProjectStageLogs';

    public $timestamps = false;

    public function logable()
    {
        return $this->morphTo();
    }

    public function operator()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'lanID');
    }
}
