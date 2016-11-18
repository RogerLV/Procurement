<?php

namespace App\Models;

use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DueDiligence extends Model
{
    use SoftDeletes;

    protected $table = "DueDiligence";

    protected $dates = ['deleted_at'];

    public static function getIns($requestID)
    {
        $requestIns = DueDiligence::find($requestID);

        if (is_null($requestIns)) {
            throw new AppException('DDMDL001');
        }

        return $requestIns;
    }
}
