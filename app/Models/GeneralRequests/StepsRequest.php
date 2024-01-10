<?php

namespace App\Models\GeneralRequests;

use App\Models\Employee\Employee;
use App\Models\Hr\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StepsRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "steps_of_approval";
    public $appends = ['departmentName', 'employeeName'];

    protected $guarded = array();

    public function requestable()
    {
        return $this->morphTo();
    }

    public function generalRequest()
    {
        return $this->belongsTo(GeneralRequest::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getDepartmentNameAttribute()
    {

        return $this->employee?->workAt?->departmentName;

    }


//    public function getDepartmentIdAttribute()
//    {
//
//        return $this->employee?->workAt?->department?->id;
//
//    }

    public function getEmployeeNameAttribute()
    {
        if ($this->employee)
            return $this->employee?->name;
        else
            return null;
    }

}
