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

    public function document()
    {
        return $this->morphMany('App\Models\Document', 'documentable');
    }

    public static function getIns($putRecordID)
    {
        $putRecordIns = self::find($putRecordID);

        if (is_null($putRecordIns)) {
            throw new AppException('PTRCDMDL001');
        }

        return $putRecordIns;
    }
}
