<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeReport;
use App\Models\Hr\Branch;
use App\Models\Hr\Management;
use App\Models\Hr\Department;
use Carbon\CarbonPeriod;
class ReportExport implements FromCollection  , ShouldAutoSize ,WithMapping ,WithHeadings,WithEvents
{

    protected $branches;
    protected $departments;
    protected $fromDate;
    protected $toDate;
    protected $departmentId;
    protected $employeeId;
    protected $timezone;
    protected $managementId;

    public function __construct($branches, $departments, $fromDate, $toDate ,$departmentId ,$employeeId,$timezone,$managementId)

    {
        $this->branches = $branches ;
        $this->departments = $departments;
        $this->fromDate = $fromDate ;
        $this->toDate = $toDate ;
        $this->departmentId = $departmentId;
        $this->managementId = $managementId;
        $this->employeeId =$employeeId;
        $this->timezone = $timezone;

    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $query = Employee::query()->with(['workAt'])->draft(0) ;
               if ($this->employeeId) {
                $query->where('id', $this->employeeId);
            }else{
                if (!empty($this->departmentId)) {
                    $query->whereHas("workAt", fn($q) => $q->where('workable_id', $this->departmentId)->where('workable_type', 'departments'));
                } elseif (!empty($this->managementId)) {
                    $query->whereHas("workAt", fn($q) => $q->where('workable_id', $this->managementId)->where('workable_type', 'managements'));
                    $departmentIds = Department::where('management_id',$this->managementId)->pluck('id')->toArray();
                    $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $departmentIds)->where('workable_type', 'departments'));
                } elseif (!empty($this->branches )) {
                    $query->whereHas("workAt", fn($q) => $q->where('workable_id',  $this->branches )->where('workable_type', 'branches'));
                    $managementIds = Management::where('branch_id', $this->branches )->pluck('id')->toArray();

                    $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $managementIds)->where('workable_type', 'managements'));
                    $departmentIds = Department::whereIn('management_id',$managementIds)->pluck('id')->toArray();
                    $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $departmentIds)->where('workable_type', 'departments'));
                }
            }

        $query->latest()->get() ;
        $ranges = CarbonPeriod::create($this->fromDate, $this->toDate);
        $list = collect();
        foreach ($ranges as $rangeDate){
            $query->with(['reports'=>fn($q) => $q->whereDate('created_at',
            $rangeDate->format('Y-m-d'))
                ,'shift'=>fn($q)=>$q->whereHas('days',fn($q)=>$q->where('day_name',$rangeDate->format('D'))),
                'workAt'=>fn($q)=>$q->with(['workable'])]);
            $empsdata= $query->latest()->get();
            $empsdata->empDate= $rangeDate->format('Y-m-d');
            $list[$rangeDate->format('Y-m-d')] = $empsdata ;
        }
        return $list;

    }

    public function map($list): array
    {
        $tickSymbol = '✓'; // Tick mark symbol
        $falseMark = '✘'; // False mark symbol


        $departments_all = Department::with('management', 'management.branch')->get();
        $managements_all = Management::with('branch')->get();

        $result = [];

        foreach ($list as $date => $employee) {

        $tickSymbol = '✓'; // Tick mark symbol
        $falseMark = '✘'; // False mark symbol

        unset($report) ;
        $report = $employee->reports->first() ?? null ;
        // Check if the reports array is empty
        if ($employee->reports->isEmpty()) {
            $symbol = $falseMark;
        } else {
            $symbol = $tickSymbol;
        }
                // Check if the reports array is empty
                if ($employee?->workAt?->workable_type === 'branches') {
                    $branch = $employee?->workAt?->workable->name;
                } elseif ($employee?->workAt?->workable_type === 'managements') {
                    foreach ($managements_all as $index_management) {
                        if ($index_management->id === $employee?->workAt?->workable_id) {
                            $branch = $index_management?->branch?->name;
                        }
                    }
                } elseif ($employee?->workAt?->workable_type === 'departments') {
                    foreach ($departments_all as $index_department) {
                        if ($index_department->id === $employee?->workAt?->workable_id) {
                            $branch = $index_department?->management?->branch?->name;
                        }
                    }
                }

                if ($employee->workAt->workable_type === 'managements') {
                    foreach ($managements_all as $index_management) {
                        if ($index_management->id === $employee?->workAt?->workable_id) {
                            $department = $index_management?->name;
                        }
                    }
                } elseif ($employee->workAt->workable_type === 'departments') {
                    foreach ($departments_all as $index_department) {
                        if ($index_department?->id === $employee?->workAt?->workable_id) {
                            $department = $index_department?->management?->name;
                        }
                    }
                }

                $result[] = [
                    $symbol,
                    $employee->name,
                    $list->empDate,
                    $branch ?? '',
                    $department ?? '',
                    $report?->start_in ? \Carbon\Carbon::parse($report?->start_in)->timezone($this->timezone)->format('h:i A') : '-',
                    $report?->end_in ? \Carbon\Carbon::parse($report?->end_in)->timezone($this->timezone)->format('h:i A') : '-' ,
                    $report?->check_in ? \Carbon\Carbon::parse($report->check_in)->timezone($this->timezone)->format('h:i A') : '-' ,
                    $report?->check_out ? \Carbon\Carbon::parse($report->check_out)->timezone($this->timezone)->format('h:i A') : '-',
                    $report?->late_hours == 0 ? '-' : secondsToHours($report?->late_hours * 60 * 60),
                    $report?->overtime_hours == 0 ? '-' : secondsToHours($report?->overtime_hours * 60 * 60),
                    $report?->work_hours == 0 ? '-' : secondsToHours($report?->work_hours * 60 * 60) ,
                    $report?->total .'  '. $report?->currency,
                ];

        }




        if( $this->branches != null){
            $employeeReport = EmployeeReport::where('employee_id', $this->employeeId)->whereBetween('created_at', [$this->fromDate  . ' 00:00:00', $this->toDate . ' 00:00:00']);

            $sumWorkHours = $employeeReport->sum('work_hours');

            $sumHourlyValue = $employeeReport->sum('hourly_value');
            $sumLateHours = $employeeReport->sum('late_hours');
            $sumOvertimeHours = $employeeReport->sum('overtime_hours');
            $totalSallry_SAR = $employeeReport->where('currency','SAR')->sum('total');
            $totalSallry_EGP = $employeeReport->where('currency','EGP')->sum('total');

            $employee_name = Employee::where('id', $this->employeeId)->first();
            if ($totalSallry_SAR !=0 && $totalSallry_EGP == 0)
                {$totalSallry = $totalSallry_SAR .'  SAR';}
            elseif ($totalSallry_SAR ==0 && $totalSallry_EGP != 0)
                {$totalSallry = $totalSallry_EGP .' EGP';}
            elseif ($totalSallry_SAR !=0 && $totalSallry_EGP != 0)
                {$totalSallry = $totalSallry_EGP .' EGP | ' .$totalSallry_SAR .' SAR';}
            else
                {$totalSallry = '0';}


            $totalLine = [
                'لاجمالي',
                $employee_name?->name,
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                secondsToHours($sumLateHours * 60 * 60),
                secondsToHours($sumOvertimeHours * 60 * 60),
                secondsToHours($sumWorkHours * 60 * 60),
                $totalSallry,
            ];
            $result[] = $totalLine;
        }
        return $result ;
    }



    public function headings(): array
    {

        return [
            '  ',
            __('names.name'),
            'التاريخ',
            'الفرع',
            'الادارة',
            'الحضور الرسمي',
            'الانصراف الرسمي',
            'الحضور الفعلي',
            'الانصراف الفعلي',
            'ساعات التأخير',
            'ساعات اضافية',
            'اجمالي ساعات اليوم',
            'الاجر اليومي',

        ];
    }

    public function registerEvents(): array
    {
        // TODO: Implement registerEvents() method.
        return [
            AfterSheet::class => function (AfterSheet $event) {
                //         $event->sheet->getDelegate()->setRightToLeft(true);
                $event->sheet->getStyle('A1:J1')->applyFromArray(
                    [
                        'font' => ['bold' => true]
                    ]);
            }
        ];
    }
}
