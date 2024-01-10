<?php

namespace App\Http\Livewire\JobType;

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

class Show extends BasicTable
{
    public $typeJob;
    public $jobName;
    public $jobTypeId;
    public $JobtypePage;
    public $search;
    protected $listeners = ['refreshJopNames' => '$refresh','confirmDelete'];
    public function search()
    {
        $jobNames = EmploymentData::with(['jobName','employee','employee.attachments'])
                ->where('job_type_id',$this->jobTypeId)
                ->whereHas("employee", fn($q) => $q->when($this->search,function ($query){
                    $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('second_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%');
                }))
                ->orderBy($this->orderBy, $this->orderDesc ? 'desc' : 'asc')
                ->get();
        return $jobNames;
    }
    public function render()
    {
        $jobNames = $this->search();
        $jobNamesSelect = JobName::get();
        $JobTypes = JobType::get();
        $JobPage = $this->JobtypePage;
        return view('livewire.job-type.show',compact('jobNames','jobNamesSelect','JobTypes','JobPage'));
    }

    public function create()
    {
        $this->emitTo('job-name.modal-form','createJobName',);
    }
    public function edit(int $jobNameId)
    {
        $this->emitTo('job-name.modal-form','editJobName',$jobNameId);
    }


    public function confirmDelete($id){

        $employee = Employee::findOrFail($id);
        if(!empty($employee->attendances) && count($employee->attendances) >= 1) {
            $this->dispatchBrowserEvent('toastr',
            ['type' => 'error', 'message' => __('message.still-has',['Model' => __('names.employee') , 'Relation' =>
            __('names.attendance-log')])]);
            return ;
        }


        if(!empty($employee->requests) && count($employee->requests) >= 1) {
             $this->dispatchBrowserEvent('toastr',
             ['type' => 'error', 'message' => __('message.still-has',['Model' => __('names.employee') , 'Relation' =>
             __('names.employee-request')])]);
             return ;
        }

        $user = User::whereId($employee->user_id)->first();
        if ($user){
            $user->delete();
        }

        $academic = EmployeeAcademic::where('employee_id', $employee->id)->get();
        if(!empty($academic)) {
            foreach($academic as $ac) {
                $ac->delete();
            }
        }

        $courses = EmployeeCourse::where('employee_id', $employee->id)->get();
        if(!empty($courses)) {
            foreach($courses as $ac) {
                 $ac->delete();
            }
        }


        $dues = EmployeeDue::where('employee_id', $employee->id)->get();
        if(!empty($dues)) {
            foreach($dues as $due) {
                $due->delete();
            }
        }


        $experinces = EmployeeExperience::where('employee_id', $employee->id)->get();
        if(!empty($experinces)) {
            foreach($experinces as $ex) {
                $ex->delete();
            }
        }

        $finances = EmployeeFinance::where('employee_id', $employee->id)->get();
        if(!empty($finances)) {
            foreach($finances as $fc) {
                $fc->delete();
            }
        }

        $info = EmployeeInfo::where('employee_id', $employee->id)->get();
        if(!empty($info)) {
            foreach($info as $if) {
                $if->delete();
            }
        }


        $vacations = EmployeeVacation::where('employee_id', $employee->id)->get();
        if(!empty($vacations)) {
            foreach($vacations as $va) {
                $va->delete();
            }
        }

        $contracts = EmploymentContract::where('employee_id', $employee->id)->get();
        if(!empty($contracts)) {
            foreach($contracts as $cs) {
                $cs->delete();
            }
        }

        $empData = EmploymentData::where('employee_id', $employee->id)->get();
        if(!empty($empData)) {
            foreach($empData as $ed) {
                $ed->delete();
            }
        }


        $employee->delete();
        $this->dispatchBrowserEvent('toastr',
        ['type' => 'success', 'message' => __('message.deleted',['model' => __('names.employee')])]);
    }

}
