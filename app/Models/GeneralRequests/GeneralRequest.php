<?php

namespace App\Models\GeneralRequests;

use App\Models\Employee\Employee;
use App\Models\Hr\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralRequest extends Model
{
    use HasFactory;

    public $guarded = array();

    public $appends = ['checkedSteps', 'nextStep', 'departmentName','employeeName'];

    public function requestable()
    {
        return $this->morphTo();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function user()
    {
        return $this->hasManyThrough(User::class,Employee::class);
    }
    public function stepsOfApproval()
    {
        return $this->belongsToMany(StepsRequest::class, 'general_request_steps_of_approval', 'general_request_id', 'steps_of_approval_id')->withTrashed()
            ->withPivot('note', 'alternative_employee_id', 'status', 'updated_at')->as('model_details');
    }


    public function stepsRequest()
    {
        return $this->hasMany(StepsRequestGenaralRequest::class);
    }

    public function firstStepsRequest()
    {
        return $this->hasOne(StepsRequestGenaralRequest::class)->orderBy('id', 'asc')->limit(1);
    }

    public function getCheckedStepsAttribute()
    {
        $steps = [];
        foreach ($this->stepsOfApproval as $step) {
            if ($step->model_details->status == -1)
                break;
            array_push($steps, $step);
        }
        return $steps;
    }

    public function getNextStepAttribute()
    {
        $nextStep = null;
        foreach ($this->stepsOfApproval as $step) {
            if ($step->model_details->status == 2 || $step->model_details->status == 0)
                break;
            if ($step->model_details->status == -1)
            {
                $nextStep = $step;
                break;
            }

        }
        if ($nextStep){
            if($nextStep->employee_id)
                 $nextStep->hasAccess = $nextStep?->employee_id == auth()->user()->employee->id;
            elseif($nextStep->department_id)
                $nextStep->hasAccess = $nextStep?->department_id == auth()->user()->employee->workAt->wokable_id ;



        }

        return $nextStep;
    }

//    public function department()
//    {
//        return $this->belongsTo(Department::class);
//    }

    public function getDepartmentNameAttribute()
    {
        if ($this->employee?->workAt)
            return $this->employee?->workAt?->departmentName;
        else
            return null;
    }

    public function getEmployeeNameAttribute()
    {
        if ($this->employee)
            return $this->employee()->pluck('name')[0];
        else
            return null;

    }

    public function getDetailsAttribute()
    {
    return $this->requestable;
    }

}
