<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemRole extends Model
{
    use SoftDeletes;

    public $table = 'SystemRoles';

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'lanID');
    }

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'dept', 'dept');
    }
}
