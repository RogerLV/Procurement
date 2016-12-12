<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreTemplate extends Model
{
    protected $table = 'ScoreTemplate';

    public function getContentAttribute($value)
    {
        return str_replace(';', '&#13;', $value);
    }

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
}
