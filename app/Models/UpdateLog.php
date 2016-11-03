<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateLog extends Model
{
    protected $table = 'UpdateLogs';
    protected $connection = 'basedata';

    public function __construct($instance, $editBy)
    {
        parent::__construct();

        $this->app = env('APP_NAME');
        $this->tableName = $instance->table;
        $this->idInTable = $instance->id;
        $this->editBy = $editBy;
    }

    public static function logInsert($instance, $editBy)
    {
        $log = new UpdateLog($instance, $editBy);
        $log->newVal = $instance->toJson();
        $log->type = 'INSERT';
        $log->save();
    }

    public static function logUpdate($instance, $editBy, $oldVal)
    {
        $log = new UpdateLog($instance, $editBy);
        $log->newVal = $instance->toJson();
        $log->type = 'UPDATE';
        $log->oldVal = json_encode($oldVal);
        $log->save();
    }

    public static function logDelete($instance, $editBy)
    {
        $log = new UpdateLog($instance, $editBy);
        $log->oldVal = json_encode($instance->getOriginal());
        $log->type = 'DELETE';
        $log->save();
    }
}
