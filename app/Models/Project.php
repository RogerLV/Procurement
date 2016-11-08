<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
