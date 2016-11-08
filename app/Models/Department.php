<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'Departments';
    protected $connection = 'basedata';

    public function users()
    {
        return $this->hasMany('App\Models\User', 'dept', 'dept');
    }
}
