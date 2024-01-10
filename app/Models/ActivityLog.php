<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ActivityLog extends Model {
    protected $table = 'activity_logs';
    protected $fillable = [
        'user_id', 'activity_loggable_id', 'activity_loggable_type','type','route', 'status', 'action',
    ];

    public function activityLoggable()
    {
        return $this->morphTo();
    }


    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function insertLog($user_id, $logable_id,$logable_type, $status, $key, $value) {
        $this->user_id = $user_id;
        $this->logable_id = $logable_id;
        $this->logable_type = $logable_type;
        $this->status  = $status;
        $this->key   = $key;
        $this->value = $value;
        return $this->save();
    }


    public static function deleteLogable($logable_id,$logable_type) {
        return static::where('logable_id', $logable_id)->where('logable_type', $logable_type)->delete();
    }

    public static function deleteLog($logable_id,$logable_type, $status) {
        return static::where('logable_id', $logable_id)->where('logable_type', $logable_type)->where('status', $status)->delete();
    }

    public static function foundLog($user_id,$logable_id,$logable_type,$status) {
        $log = static::where('user_id', $user_id)->where('logable_id', $logable_id)->where('logable_type', $logable_type)->where('status', $status)->first();
        if (isset($log)) {
            return $log->id;
        } else {
            return 0;
        }
    }

}
