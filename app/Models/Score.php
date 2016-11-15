<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'Scores';

    public function vendors()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendorID', 'id');
    }
}
