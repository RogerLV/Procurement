<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'Vendors';

    public static function getIns($vendorID)
    {
        $vendorIns = Vendor::find($vendorID);

        if (is_null($vendorIns)) {
            throw new AppException('VDRMDL001', 'Data Error');
        }

        return $vendorIns;
    }
}
