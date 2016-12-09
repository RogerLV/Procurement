<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AppException;

class ReviewMeeting extends Model
{
    protected $table = 'ReviewMeetings';

    public static function getIns($reviewMeetingID)
    {
        // Log is heavily used throughout processing, so retrieve in advance
        $reviewMeetingIns = self::with('log.operator')->find($reviewMeetingID);

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

    public function metaInfo()
    {
        return $this->hasOne('App\Models\MeetingMinutesMetaInfo', 'reviewMeetingID', 'id');
    }

    public function getDateAttribute($value)
    {
        if (is_null($value)) {
            return $value;
        }

        $dateAry = explode('-', $value);
        return $dateAry[0].YEAR.$dateAry[1].MONTH.$dateAry[2].DAY;
    }
}
