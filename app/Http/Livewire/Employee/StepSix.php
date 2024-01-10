<?php

namespace App\Http\Livewire\Employee;

use App\Http\Livewire\Basic\BasicForm;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeAcademic;
use App\Models\Employee\EmployeeCourse;
use App\Models\Employee\EmployeeExperience;
use Livewire\Component;

class StepSix extends BasicForm
{

    public $employee;
    public $academics;
    public $cources;
    public $experience;
    public $academic;
    public function mount($employee_id) {
        $this->employee = Employee::findOrFail($employee_id);
        $this->cources = EmployeeCourse::where('employee_id',$employee_id)->where('certificate_photo','!=',null)->count();
        $this->experience = EmployeeExperience::where('employee_id',$employee_id)->where('photo','!=',null)->count();
        $this->academic = EmployeeAcademic::where('employee_id',$employee_id)->where('qualification_photo','!=',null)->count();
    }

    public function render()
    {
        return view('livewire.employee.step-six');
    }
}
