<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'Conversation';

    public function conversable()
    {
        return $this->morphTo();
    }

    public function composer()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'lanID');
    }
}
