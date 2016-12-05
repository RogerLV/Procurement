<?php

namespace App\Models;

use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewTopic extends Model
{
    use SoftDeletes;

    public $table = 'ReviewTopics';
    protected $dates = ['deleted_at'];

    public function topicable()
    {
        return $this->morphTo();
    }

    public function reviewMeeting()
    {
        return $this->belongsTo('App\Models\ReviewMeeting', 'reviewMeetingID', 'id');
    }

    public function meetingMinutesContent()
    {
        return $this->hasOne('App\Models\MeetingMinutesContent', 'topicID', 'id');
    }

    public static function getIns($topicID)
    {
        $topicIns = self::find($topicID);

        if (is_null($topicIns)) {
            throw new AppException('TPCMDL001');
        }

        return $topicIns;
    }
}
