<?php

namespace App\Models;

use App\Models\Employee\Employee;
use App\Models\Hr\Branch;
use App\Models\Hr\Department;
use App\Models\Hr\Management;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Znck\Eloquent\Traits\BelongsToThrough;

class WorkAt extends MainModel
{
    use HasFactory, BelongsToThrough;


    // protected $table = "work_ats";
    protected $appends = ['employeeName', 'departmentName'];
    protected $fillable = [
        'employee_id',
        'workable_type',
        'workable_id'
    ];

    public function workable()
    {
        return $this->morphTo();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function branches()
    {
        return $this->morphedByMany(Branch::Class, 'workable','work_ats');
    }

    public function managements()
    {
        return $this->morphedByMany(Management::Class, 'workable','work_ats');
    }

    public function departments()
    {
        return $this->morphedByMany(Department::Class, 'workable','work_ats');
    }

    public function getEmployeeNameAttribute()
    {
        return count($this->employee()->pluck('first_name')) ? $this->employee()->pluck('first_name')[0] : '';
    }

    public function getDepartmentNameAttribute()
    {
        return count(Department::where('id', $this->workable_id)->get()) ? Department::where('id', $this->workable_id)->first()->name : '';
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'workable_id', 'id');
    }
}
