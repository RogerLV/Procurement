<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negotiation extends Model
{
    protected $table = 'Negotiations';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'lanID' ,'lanID');
    }
}
