<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'Users';
    protected $connection = 'basedata';

    public function getUCnNameAttribute($value)
    {
        return 'NULL' == $value ? '' : $value;
    }

    public function scopeInService($query)
    {
        return $query->where('inService', true);
    }
}
