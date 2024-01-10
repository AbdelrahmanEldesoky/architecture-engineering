<?php

namespace App\Http\Livewire\Settings\DateEnd;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\City;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeAcademic;
use App\Models\Employee\EmployeeCourse;
use App\Models\Employee\EmployeeDue;
use App\Models\Employee\EmployeeExperience;
use App\Models\Employee\EmployeeFinance;
use App\Models\Employee\EmployeeInfo;
use App\Models\Hr\Department;
use App\Models\Hr\Management;
use App\Models\Renge;
use App\Models\SmsEndDate;
use App\Models\User;
use App\Services\CityService;
use Carbon\Carbon;
use Livewire\Component;
use App\Traits\SmsTrait;
use App\Traits\AttendanceTrait;
class Table extends BasicTable
{
    use AttendanceTrait,SmsTrait;
    protected $listeners = ['confirmDelete'];

    public $perPage = 100 ;
    
    public function search(){
        $departments_all = Department::with('management','management.branch')->get();
        $managements_all = Management::with('branch')->get();
        $date = Carbon::today();
        $date_today = $date->format('Y-m-d');
        $renge = Renge::first();
        $date_in_count_days = $date->addDays($renge->renge_count)->format('Y-m-d');

        $employeeInfos = EmployeeInfo::where(function ($query) use ($date_today, $date_in_count_days) {
            $query->where('end_id_number', '<=', $date_in_count_days)
                ->orWhere('end_medical_insurance', '<=', $date_in_count_days)
                ->orWhere('end_saudi_authority', '<=', $date_in_count_days);
        })->with(['employee'])
        ->whereHas("employee", function ($q) {
            $q->when($this->search, function ($query) {
                $searchTerm = '%'.$this->search.'%';
                $query->where(function ($innerQuery) use ($searchTerm) {
                    $innerQuery->where('name', 'like', $searchTerm)
                        ->orWhere('full_name', 'like', $searchTerm)
                        ->orWhere('first_name', 'like', $searchTerm)
                        ->orWhere('second_name', 'like', $searchTerm)
                        ->orWhere('last_name', 'like', $searchTerm);
                });
            });
        })
        ->get();
            return $employeeInfos;
    }
    public function render()
    { 

        $employeeInfos = $this->search();
        $departments_all = Department::with('management','management.branch')->get();
        $managements_all = Management::with('branch')->get();
        $date = Carbon::today();
        $date_today = $date->format('Y-m-d');
        $renge = Renge::first();
        $date_in_count_days = $date->addDays($renge->renge_count)->format('Y-m-d');

        return view('livewire.settings.date-end.table',[
            'employeeInfos' => $employeeInfos,
            'date_today' => $date_today,
            'date_in_count_days' => $date_in_count_days,
            'departments_all'=>$departments_all,
            'managements_all'=>$managements_all,
            'renge' =>$renge,
        ]);
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
