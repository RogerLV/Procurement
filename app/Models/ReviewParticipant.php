<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewParticipant extends Model
{
    use SoftDeletes;

    protected $table = 'ReviewParticipants';

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'lanID');
    }
}
