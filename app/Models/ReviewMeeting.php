<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AppException;

class ReviewMeeting extends Model
{
    protected $table = 'ReviewMeetings';

    public static function getIns($reviewMeetingID)
    {
        $reviewMeetingIns = self::find($reviewMeetingID);

        if (is_null($reviewMeetingIns)) {
            throw new AppException('RVWMDL001');
        }

        return $reviewMeetingIns;
    }

    public function topics()
    {
        return $this->hasMany('App\Models\ReviewTopic', 'reviewMeetingID', 'id');
    }

    public function participants()
    {
        return $this->hasMany('App\Models\ReviewParticipant', 'reviewMeetingID', 'id');
    }

    public function log()
    {
        return $this->morphMany('App\Models\StageLog', 'logable');
    }
}
