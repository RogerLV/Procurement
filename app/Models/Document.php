<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $table = 'Documents';

    public function documentable()
    {
        return $this->morphTo();
    }

    public function uploader()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'lanID');
    }

    public function getFullPath()
    {
        return env('STORAGE_PATH').'/'
            .$this->subAddress.'/'
            .$this->tempName.'.'
            .$this->ext;
    }
}
