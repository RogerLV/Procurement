<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemRole extends Model
{
    use SoftDeletes;

    protected $table = 'SystemRoles';

    protected $dates = ['deleted_at'];
}
