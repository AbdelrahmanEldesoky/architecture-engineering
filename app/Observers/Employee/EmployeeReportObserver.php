<?php

namespace App\Observers\Employee;

use App\Models\Employee\EmployeeReport;

class EmployeeReportObserver
{
    /**
     * Handle the EmployeeReport "created" event.
     */
    public function created(EmployeeReport $employeeReport): void
    {
    $this->calTotal($employeeReport);
    }

    /**
     * Handle the EmployeeReport "updated" event.
     */
    public function updated(EmployeeReport $employeeReport): void
    {
        $this->calTotal($employeeReport);
    }

    /**
     * Handle the EmployeeReport "deleted" event.
     */
    public function deleted(EmployeeReport $employeeReport): void
    {
        //
    }

    /**
     * Handle the EmployeeReport "restored" event.
     */
    public function restored(EmployeeReport $employeeReport): void
    {
        //
    }

    /**
     * Handle the EmployeeReport "force deleted" event.
     */
    public function forceDeleted(EmployeeReport $employeeReport): void
    {
        //
    }

    private function calTotal(EmployeeReport $employeeReport){
        $employeeReport->total = round( ($employeeReport->shift_working_hours * $employeeReport->hourly_value)
            + ($employeeReport->overtime_hours * $employeeReport->overtime_hour_value)
            - ($employeeReport->late_hours * $employeeReport->late_hour_value) ,2);
           $employeeReport->saveQuietly();
    }
}
