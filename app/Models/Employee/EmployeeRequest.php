<?php

namespace App\Models\Employee;

use App\Models\GeneralRequests\GeneralRequest;
use App\Models\MainModelSoft;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends MainModelSoft
{
//    protected $fillable = ['type', 'reason' , 'project_name','name', 'responsible', 'time_from','time_to', 'latitude', 'longitude','address','from', 'response', 'employee_id', 'status_id', 'time_valid_to' , 'time_valid_in_seconds',"time_zone",""];
    // protected $casts = ['time_from'=>'datetime','time_to'=>'datetime'];
    protected $appends=['typeInArabic'];
protected $guarded=[];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function generalRequest()
    {
        return $this->morphOne(GeneralRequest::CLass, 'requestable');
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function getFromTimeAttribute()
    {
        return Date('h:i A', strtotime($this->time_from));
    }

    public function getToTimeAttribute()
    {
        return Date('h:i A', strtotime($this->time_to));
    }

    public function getTypeInArabicAttribute()
    {
        return "مهمة العمل";
    }


}
