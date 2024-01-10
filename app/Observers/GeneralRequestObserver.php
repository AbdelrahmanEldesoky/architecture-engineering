<?php

namespace App\Observers;

use App\Models\Employee\EmployeeRequest;
use App\Models\GeneralRequest;
use App\Models\GeneralRequests\GeneralRequestAdvance;
use App\Models\GeneralRequests\GeneralRequestCustody;
use App\Models\GeneralRequests\GeneralRequestMaintenanceCar;
use App\Models\GeneralRequests\GeneralRequestVacation;
use App\Models\GeneralRequests\GeneralRequestWorkNeed;
use App\Models\GeneralRequests\StepsRequest;
use App\Models\GeneralRequests\StepsRequestGenaralRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GeneralRequestObserver
{
    /**
     * Handle the GeneralRequest "created" event.
     */
    public function created(model $model): void
    {
        if ($model instanceof GeneralRequestMaintenanceCar)
            $general_request = $model->generalRequest()->create(['employee_id' => auth('api')->user()->employee->id, 'type' => 6]);
        elseif ($model instanceof GeneralRequestWorkNeed)
            $general_request = $model->generalRequest()->create(['employee_id' => auth('api')->user()->employee->id, 'type' => 5]);
        elseif ($model instanceof GeneralRequestAdvance)
            $general_request = $model->generalRequest()->create(['employee_id' => auth('api')->user()->employee->id, 'type' => 3]);
        elseif ($model instanceof GeneralRequestCustody)
            $general_request = $model->generalRequest()->create(['employee_id' => auth('api')->user()->employee->id, 'type' => 4]);
        elseif ($model instanceof GeneralRequestVacation)
            $general_request = $model->generalRequest()->create(['employee_id' => auth('api')->user()->employee->id, 'type' => 1]);
        elseif ($model instanceof EmployeeRequest) {
            $general_request = $model->generalRequest()->create(['employee_id' => auth('api')->user()->employee->id, 'type' => 2]);
            try {
                $stepRequest=StepsRequest::create([
                    'employee_id'=>$model->responsible_employee_id,
                    'action'=>1,
                    'duration'=>24,
                    'model'=>1,
                    'type'=>-1,
                ]);
                StepsRequestGenaralRequest::query()->create(['general_request_id' => $general_request->id, 'steps_of_approval_id' => $stepRequest->id, 'status' => -1]);
            }
            catch (\Exception  $e){

            }



        }
        $steps = StepsRequest::query()->where('type', $general_request->type)->orderBy('id', 'ASC')->get();
        foreach ($steps as $step) {
            StepsRequestGenaralRequest::query()->create(['general_request_id' => $general_request->id, 'steps_of_approval_id' => $step->id, 'status' => -1]);
        }


    }

    /**
     * Handle the GeneralRequest "updated" event.
     */
    public function updated(model $model): void
    {
        //
    }

    /**
     * Handle the GeneralRequest "deleted" event.
     */
    public function deleted(model $model): void
    {
        //
    }

    /**
     * Handle the GeneralRequest "restored" event.
     */
    public function restored(model $model): void
    {
        //
    }

    /**
     * Handle the GeneralRequest "force deleted" event.
     */
    public function forceDeleted(model $model): void
    {
        //
    }
}
