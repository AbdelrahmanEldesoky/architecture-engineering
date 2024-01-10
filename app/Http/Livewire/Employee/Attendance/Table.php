<?php

namespace App\Http\Livewire\Employee\Attendance;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Attendance;
use App\Models\Employee\Employee;
use App\Models\Hr\Branch;
use App\Models\Hr\Management;
use App\Models\Hr\Department;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Table extends BasicTable
{

    public $branches = [];
    public $managements = [];
    public $departments = [];
    public $employees = [];
    public  $emps = [];

    public $timezone = "Africa/Cairo";

    public $branchId, $managementId, $departmentId, $employeeId, $fromDate, $toDate;
    public $ids = [], $empId;

    public function mount(Request $request)
    {
        $this->branches = Branch::pluck('name', 'id')->toArray();
        // $this->attendances = Attendance::whereDate('created_at', now())->get();
        $this->fromDate = date("Y-m-d");
        $this->toDate = date("Y-m-d");
        $this->searchEmployees();
        $timezone = timezone($request->ip());
        if ($timezone != "") {
            $this->timezone = $timezone;
        }


    }

    public function render()
    {
        $employeesGroups = $this->searchEmployees();
        return view('livewire.employee.attendance.table',compact('employeesGroups'));
    }


    public function updatedBranchId($data)
    {
        $this->managements = Management::where('branch_id', $data)->pluck('name', 'id')->toArray();
//        $this->searchEmployees();
    }

    public function updatedManagementId($data)
    {
        $this->departments = Department::where('management_id', $data)->pluck('name', 'id')->toArray();
//        $this->searchEmployees();
    }

    public function updatedDepartmentId($data)
    {
//        $this->searchEmployees();
    }

    public function updatedFromDate($data)
    {
       if (Carbon::hasFormat($data, 'Y-m-d') && Carbon::createFromFormat('Y-m-d', $data)->isValid()) {

//           if (  Carbon::parse($this->fromDate)->format('Y') !== Carbon::parse(now()->format('Y'))) {
//               $this->fromDate = Carbon::create(Carbon::now()->year, 1, 1)->format('Y-m-d');
//           }else{
               $this->fromDate =  $data ;
//           }
       }else{
           $this->fromDate =  date("Y-m-d") ;
       }
    }

    public function updatedToDate($data)
    {
        if (Carbon::hasFormat($data, 'Y-m-d') && Carbon::createFromFormat('Y-m-d', $data)->isValid()) {
//            if (  Carbon::parse($this->toDate)->format('Y') !== Carbon::parse(now()->format('Y'))) {
//                $this->toDate = Carbon::create(Carbon::now()->year, 12, 31)->format('Y-m-d');
//            }else{
                $this->toDate =  $data ;
//            }
        }else{
            $this->toDate =  date("Y-m-d") ;
        }
    }

    public function updatedEmployeeId($data)
    {
        $this->empId = $data;
    }


    public function searchEmployees()
    {
        $query = Employee::query()->draft(0);
        if ($this->employeeId) {
            $query->where('id', $this->employeeId);
        }else{
            if (!empty($this->departmentId)) {
                $query->whereHas("workAt", fn($q) => $q->where('workable_id', $this->departmentId)->where('workable_type', 'departments'));
            } elseif (!empty($this->managementId)) {
                $query->whereHas("workAt", fn($q) => $q->where('workable_id', $this->managementId)->where('workable_type', 'managements'));
                $departmentIds = Department::where('management_id',$this->managementId)->pluck('id')->toArray();
                $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $departmentIds)->where('workable_type', 'departments'));
            } elseif (!empty($this->branchId)) {
                $query->whereHas("workAt", fn($q) => $q->where('workable_id', $this->branchId)->where('workable_type', 'branches'));
                $managementIds = Management::where('branch_id',$this->branchId)->pluck('id')->toArray();
                $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $managementIds)->where('workable_type', 'managements'));
                $departmentIds = Department::whereIn('management_id',$managementIds)->pluck('id')->toArray();
                $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $departmentIds)->where('workable_type', 'departments'));
            }
        }
        $this->emps = $query->with(['workAt'])->latest()->get() ;
        $ranges = CarbonPeriod::create($this->fromDate, $this->toDate);
        $list = collect();
        foreach ($ranges as $rangeDate){
            $query->with(['reports'=>fn($q) => $q->whereDate('created_at',
            $rangeDate->format('Y-m-d'))
                ,'shift'=>fn($q)=>$q->whereHas('days',fn($q)=>$q->where('day_name',$rangeDate->format('D'))),
                'workAt'=>fn($q)=>$q->with(['workable'])]);
            $empsdata= $query->latest()->get();
            $list[$rangeDate->format('Y-m-d')] = $empsdata ;
        }

        return $list;
    }


   public function confirmDelete($id)
   {
       Attendance::findOrFail($id)->delete();
       $this->dispatchBrowserEvent('toastr',
           ['type' => 'success', 'message' => __('message.deleted', ['model' => __('names.attend-in')])]);
   }

}
