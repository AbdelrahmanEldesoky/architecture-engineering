<?php

namespace App\Http\Livewire\JobName;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeAcademic;
use App\Models\Employee\EmployeeCourse;
use App\Models\Employee\EmployeeDue;
use App\Models\Employee\EmployeeExperience;
use App\Models\Employee\EmployeeFinance;
use App\Models\Employee\EmployeeInfo;
use App\Models\Employee\EmployeeVacation;
use App\Models\Employee\EmploymentContract;
use App\Models\Employee\EmploymentData;
use App\Models\Hr\JobName;
use App\Models\Hr\JobType;
use App\Models\User;
use App\Services\JobNameService;
use Livewire\Component;

class Details extends BasicTable
{
    public $typeJob;
    public $jobName;
    public $jobTypeId;
    public $JobtypePage;
    public $jobNameID;
    public $search;
    protected $listeners = ['refreshJopNames' => '$refresh','confirmDelete'];
    public function search()
    {
        $jobNames = JobName::with('jobType')
                ->where('id',$this->jobTypeId)->first();

        return $jobNames;
    }
    public function render()
    {
        $jobNames = $this->search();

        return view('livewire.job-name.details',compact('jobNames'));
    }


}
