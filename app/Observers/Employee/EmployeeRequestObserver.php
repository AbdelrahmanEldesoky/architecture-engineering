<?php

namespace App\Observers\Employee;

use App\Models\Employee\EmployeeRequest;
use App\Models\Status;

class EmployeeRequestObserver
{
    /**
     * Handle the EmployeeRequest "created" event.
     */
    public function created(EmployeeRequest $employeeRequest): void
    {
        //
    }

    /**
     * Handle the EmployeeRequest "updated" event.
     */
    public function updated(EmployeeRequest $employeeRequest): void
    {
        $employeeRequest->status_id ;
    }

    /**
     * Handle the EmployeeRequest "deleted" event.
     */
    public function deleted(EmployeeRequest $employeeRequest): void
    {
        //
    }

    /**
     * Handle the EmployeeRequest "restored" event.
     */
    public function restored(EmployeeRequest $employeeRequest): void
    {
        //
    }

    /**
     * Handle the EmployeeRequest "force deleted" event.
     */
    public function forceDeleted(EmployeeRequest $employeeRequest): void
    {
        //
    }

    private function cal(EmployeeRequest $employeeRequest)
    {
//        $statusId = Status::where('type','employee-requests')->where('name','accepted')->value('id');
//        if ($employeeRequest->status_id == $statusId){
//            // get from component
//        }
    }
}
