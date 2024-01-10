<?php

namespace App\Http\Livewire\Employee;

use App\Exports\ReportExport;
use App\Http\Livewire\Basic\BasicTable;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeReport;
use App\Models\Hr\Branch;
use App\Models\Hr\Management;
use App\Models\Hr\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Report extends BasicTable
{

    public $branches = [];
    public $managements = [];
    public $departments = [];
    public $emps = [];

    public $timezone = "Africa/Cairo";

    public $branchId, $managementId, $departmentId, $employeeId, $fromDate, $toDate;
    public $ids = [], $empId;


    public function mount(Request $request)
    {
        $this->branches = Branch::pluck('name', 'id')->toArray();

        $this->departments = Department::pluck('name', 'id')->toArray();
        $this->fromDate = date("Y-m-d");
        $this->toDate = date("Y-m-d");

        $timezone = timezone($request->ip());
        if ($timezone != "") {
            $this->timezone = $timezone;
        }

    }

    public function export()
    {
        $export = new ReportExport($this->branchId, $this->departmentId, $this->fromDate, $this->toDate, $this->departmentId, $this->employeeId, $this->timezone, $this->managementId);
        return Excel::download($export, 'filename.xlsx');

    }

    public function render()
    {

        $departments_all = Department::with('management', 'management.branch')->get();
        $managements_all = Management::with('branch')->get();


        $query = Employee::query()->with(['workAt'])->draft(0);
        if ($this->employeeId) {
            $query->where('id', $this->employeeId);
        } else {
            if (!empty($this->departmentId)) {
                $query->whereHas("workAt", fn($q) => $q->where('workable_id', $this->departmentId)->where('workable_type', 'departments'));
            } elseif (!empty($this->managementId)) {
                $query->whereHas("workAt", fn($q) => $q->where('workable_id', $this->managementId)->where('workable_type', 'managements'));
                $departmentIds = Department::where('management_id', $this->managementId)->pluck('id')->toArray();
                $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $departmentIds)->where('workable_type', 'departments'));
            } elseif (!empty($this->branchId)) {
                $query->whereHas("workAt", fn($q) => $q->where('workable_id', $this->branchId)->where('workable_type', 'branches'));
                $managementIds = Management::where('branch_id', $this->branchId)->pluck('id')->toArray();
                $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $managementIds)->where('workable_type', 'managements'));
                $departmentIds = Department::whereIn('management_id', $managementIds)->pluck('id')->toArray();
                $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $departmentIds)->where('workable_type', 'departments'));
            }
        }

        $query->latest()->get();
        $count_employees = $query->count();
        if ($this->toDate == "")
            $this->toDate = date("Y-m-d");
        if ($this->fromDate == "")
            $this->fromDate = date("Y-m-d");
        if ($this->fromDate > $this->toDate) {
            $temp = $this->fromDate;
            $this->fromDate = $this->toDate;
            $this->toDate = $temp;

        }

        $ranges = CarbonPeriod::create($this->fromDate, $this->toDate);
        $list = collect();
        foreach ($ranges as $rangeDate) {
            $query->with(['reports' => fn($q) => $q->whereDate('created_at',
                $rangeDate->format('Y-m-d'))
                , 'shift' => fn($q) => $q->whereHas('days', fn($q) => $q->where('day_name', $rangeDate->format('D'))),
                'workAt' => fn($q) => $q->with(['workable'])]);

            $empsdata = $query->latest()->get();
            $list[$rangeDate->format('Y-m-d')] = $empsdata;
        }

        $employeeReport = EmployeeReport::where('employee_id', $this->employeeId)->whereBetween('created_at', [$this->fromDate . ' 00:00:00', $this->toDate . ' 00:00:00']);

        $sumWorkHours = $employeeReport->sum('work_hours');

        $sumHourlyValue = $employeeReport->sum('hourly_value');
        $sumLateHours = $employeeReport->sum('late_hours');
        $sumOvertimeHours = $employeeReport->sum('overtime_hours');
        $totalSallry_SAR = $employeeReport->where('currency', 'SAR')->sum('total');
        $totalSallry_EGP = $employeeReport->where('currency', 'EGP')->sum('total');

        $employee = Employee::where('id', $this->employeeId)->first();


        $date = $this->fromDate;
        $employee_present_count = 0;

        if ($this->fromDate == $this->toDate) {
            $employee_present_count = $query->whereHas('reports', function ($q) use ($date) {
                $q->whereBetween('created_at', [
                    $date . ' 00:00:00',
                    $date . ' 23:59:59'
                ]);
            })
                ->count();
        }


        $employee_absence_count = $count_employees - $employee_present_count;

        return view('livewire.employee.report', ['employees' => $list,
            'managements_all' => $managements_all,
            'departments_all' => $departments_all,
            'sumWorkHours' => secondsToHours($sumWorkHours * 60 * 60),
            'sumHourlyValue' => secondsToHours($sumHourlyValue * 60 * 60),
            'totalSallry_SAR' => round($totalSallry_SAR),
            'totalSallry_EGP' => round($totalSallry_EGP),
            'sumLateHours' => secondsToHours($sumLateHours * 60 * 60),
            'sumOvertimeHours' => secondsToHours($sumOvertimeHours * 60 * 60),
            'name' => $employee?->name,
            'employee_present_count' => $employee_present_count,
            'employee_absence_count' => $employee_absence_count,
            'count_employees' => $count_employees,
        ]);

    }


}
