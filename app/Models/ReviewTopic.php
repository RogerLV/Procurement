<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewTopic extends Model
{
    public $table = 'ReviewTopics';

    public function topicable()
    {
        return $this->morphTo();
    }
}
