<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreItem extends Model
{
    protected $table = 'ScoreItems';

    public function scores()
    {
        return $this->hasMany('App\Models\Score', 'itemID', 'id');
    }

    public function getContentAttribute($value)
    {
        return str_replace(';', '&#13;', $value);
    }
}
