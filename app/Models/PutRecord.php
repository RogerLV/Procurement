<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PutRecord extends Model
{
    public $table = 'PutRecords';

    public function topics()
    {
        return $this->morphMany('App\Models\ReviewTopic', 'topicable');
    }
}
