<?php

namespace App\Http\Controllers\api\Employee;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Requests\createStepsApprovaleRequest;
use App\Http\Requests\MakeDeccissionGeneralRequest;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeReport;
use App\Models\Employee\EmployeeRequest;
use App\Models\Employee\EmployeeVacation;
use App\Models\GeneralRequests\GeneralRequest;
use App\Models\GeneralRequests\GeneralRequestAdvance;
use App\Models\GeneralRequests\GeneralRequestCustody;
use App\Models\GeneralRequests\GeneralRequestMaintenanceCar;
use App\Models\GeneralRequests\GeneralRequestVacation;
use App\Models\GeneralRequests\GeneralRequestWorkNeed;
use App\Models\GeneralRequests\StepsRequest;
use App\Models\Hr\ShiftDay;
use App\Models\Status;
use App\Models\User;
use App\Models\WorkAt;
use App\Notifications\MainNotification;
use App\Services\FCM\FCMService;
use App\Traits\AttendanceTrait;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GeneralRequestController extends MainController
{
    use AttendanceTrait;
    public $employeeRequest;

    public function createCarMaintenance(Request $request)
    {
//        return base64_decode(request()->builder[0]['image']);
        $rules = [
            'builder' => 'required|array',
            'builder.*.description' => 'required|string',
            'builder.*.image' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(["message" => "validation Error", 'error' => $errors]);
        }
        return response(['maintenance_car' => GeneralRequestMaintenanceCar::create(array_merge($request->data, ['employee_id' => $this->getEmployeeForUser()->id]))
            ->builder()->createMany(array_map(fn($item) => ['description' => $item['description']], $request->all()['builder']))]);
    }

    public function createWorksNeeds(Request $request)
    {
        return response(['work_needs' => GeneralRequestWorkNeed::create(array_merge($request->data, ['employee_id' => $this->getEmployeeForUser()->id]))->builder()->createMany($request->builder)]);
    }

    public function createAdvance(Request $request)
    {
        return response(['advance' => GeneralRequestAdvance::create(array_merge($request->data, ['employee_id' => $this->getEmployeeForUser()->id]))]);
    }

    public function createCustody(Request $request)
    {
        $custody = GeneralRequestCustody::create(array_merge($request->data, ['employee_id' => $this->getEmployeeForUser()->id]));
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
        $filePath = storage_path('app/public/image.png');
        file_put_contents($filePath, $imageData);
        $custody->addMedia($filePath)->toMediaCollection('images');
        return response(['custody' => $custody]);

    }


    public function createVacation(Request $request)
    {
        return response(['vacation' => GeneralRequestVacation::create(array_merge($request->data, ['employee_id' => $this->getEmployeeForUser()->id]))]);

    }

    public function getAllRequestsWithDetails(Request $request)

    {

        $type = GeneralRequest::selectRaw
        ("COUNT(type) AS count , type")
            ->groupBy("type")
            ->orderBy('type', "ASC")
            ->get();
        $arr = [];
        for ($i = 0; $i <= 5; $i++) {
            $arr[$i] = ['type' => $i + 1, 'count' => 0];
        }
        foreach ($type as $t) {
            $arr[$t->type - 1] = ['type' => $t->type, 'count' => $t->count];
        }
        if($request->has('sdate') && $request->has('edate'))
        {
            if($request->sdate > $request->edate)
            {
                $temp= $request->sdate;
                $request->sdate = $request->edate;
                $request->edate = $temp;
            }
        }
//        return  $request->has('action') && $request->action ==0;
        return response(['requests' => GeneralRequest::query()->
        when($request->has('status'), function ($query) use ($request) {
            return $query->where('status', $request->status);
        })->when($request->has('order'), function ($query) use ($request) {
            return $query->orderBy('created_at', "$request->order");
        })->when($request->has('type'), function ($query) use ($request) {
            return $query->where('type', $request->type);
        })
            ->when($request->has('action') && $request->action == 0, function ($query) use ($request) {
                return $query->where('in_progress', 0);
            })
            ->when($request->has('action') && $request->action == 1, function ($query) use ($request) {

                return $query->where('in_progress', 1);

            })
            ->when($request->has('sdate'), function ($query) use ($request) {
                return $query->whereDate('created_at', '>=', Carbon::createFromFormat("Y-m-d H:i:s", $request->sdate));
            })
            ->when($request->has('edate'), function ($query) use ($request) {
                return $query->whereDate('created_at', '<=', Carbon::createFromFormat("Y-m-d H:i:s", $request->edate));
            })
            ->when($request->has('department_id'), function ($query) use ($request) {
                return $query->whereHas('employee', function ($query) use ($request) {
                    $query->whereHas('WorkAt',function ($q) use ($request) {
                       $q->where('workable_id',$request->department_id);
                    });
                });
            })
            ->when($request->has('search'), function ($query) use ($request) {
                return $query->whereHas('employee', function ($query) use ($request) {
                    $query->where('name', 'LIKE', "%" . $request->search . "%");
                });
            })

            ->when(auth()->user()->roleCheck->role_id !=1 ,function ($query) {
                $query->whereHas('stepsOfApproval', function ($query) {
                    $query->where('employee_id', $this->getEmployeeForUser()->id);
                });
            })->with(['requestable'])->get()
            , 'count' => $arr, 'status' => 200]);
    }

    public function getDetailsForRequest(GeneralRequest $generalRequest)
    {
        return response(['request' => GeneralRequest::where('id', $generalRequest->id)->with('requestable')->first(), 'status' => 200]);
    }

    public function createSteps(Request $request)
    {
        $generalRequest = StepsRequest::where('type', $request->data[0]['type']);


        if (!$generalRequest->get()->count()) {
            return response(['steps' => StepsRequest::insert($request->data)]);
        }
        $generalRequest->delete();
        return response(['steps' => StepsRequest::insert($request->data)]);

    }


    public function getSteps(Request $request)
    {

        return response(['steps' => StepsRequest::where('type', $request->type)->get()]);
    }

    public function getDepartment()
    {
        return response(['employee' => WorkAt::all()->groupBy('workable_id')]);
    }


    public function getMyRequests(Request $request)
    {

        return response(['my_requests' => GeneralRequest::where('employee_id', $this->getEmployeeForUser()->id)
            ->when($request->has('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })->when($request->has('order'), function ($query) use ($request) {
                return $query->orderBy('created_at', "$request->order");
            })->when($request->has('type'), function ($query) use ($request) {
                return $query->where('type', $request->type);
            })
            ->with('requestable')->get()]);
    }

    public function last_vance()
    {
        return response(['advance' => GeneralRequestAdvance::where('employee_id', $this->getEmployeeForUser()->id)->orderBy('id', 'DESC')->first(), 'employee' => Employee::find($this->getEmployeeForUser()->id)->finance]);

    }

    public function last_Custody()
    {
        return response(['custody' => GeneralRequestCustody::where('employee_id', $this->getEmployeeForUser()->id)->orderBy('id', 'DESC')->first()]);

    }

    public function approve_steps(MakeDeccissionGeneralRequest $request, GeneralRequest $generalRequest)
    {
        $stepAction = null;

        foreach ($generalRequest->stepsRequest as $step) {
//            return response(['emp_id'=>StepsRequest::find($step->model_details->steps_of_approval_id)->employee_id,'my_id'=>$this->getEmployeeForUser()->id]);


            if ($step->status == -1) {
                if (StepsRequest::withTrashed()->find($step->steps_of_approval_id)->employee_id != $this->getEmployeeForUser()->id)
                    return response(['status' => false, 'message' => 'employee not authorised in this stage'], 403);
                $stepAction = $step;
                $step->update(['status' => $request->status, 'note' => $request->note, 'alternative_employee_id' => $request->employee_id]);
                $generalRequest->update(['in_progress' => 1]);
                if ($request->status == 0)
                    $generalRequest->update(['status' => 0]);
                elseif ($request->status == 2) {
                    $generalRequest->update(['status' => 2]);

                }

                $lastStatus = $generalRequest->stepsRequest()->orderBy('id', 'desc')->first()?->status;
                if ($lastStatus == 1)
                    $generalRequest->update(['status' => 1]);
                elseif ($lastStatus == 2)
                    $generalRequest->update(['status' => 2]);

                if ($generalRequest->status == 1 || $generalRequest->status == 2) {
                    if ($generalRequest->requestable instanceof EmployeeRequest) {
                        $this->workMission($generalRequest->requestable);
                        $user = User::whereHas('employee', fn($q) => $q->whereHas('requests', fn($q) => $q->where('id', $generalRequest->requestable->id)))->first();
                        $data = [];
                        $data['from'] = config('app.name');
                        $data['message'] = 'تم الرد علي طلبك ' . ",تم تغيير حالة طلبك إلي " .
                            __('names.' . $this->employeeRequest->status?->name);
                        $user->notify(new MainNotification($data));
                        $data['message_sms'] = 'الموظف ' . $this->employeeRequest->employee?->first_name . 'تم الرد علي طلب ' . ",تم تغيير حالة طلب إلي " .
                            __('names.' . $this->employeeRequest->status?->name);
//        $this->sendSmsMessageMora(["0538500542"],$data['message_sms']);
                        $fcm = new FCMService();
                        $fcm->sendNotification([$user->id], "تم الرد علي طلب " .
                            $this->employeeRequest->name, $data['message']
                            , null, null, null, "users");
                    } elseif ($generalRequest->requestable instanceof GeneralRequestVacation) {

                        $vacation = Employee::query()->find($generalRequest->requestable->employee_id)->vacation;
//                        return (int)$vacation->vacation_credit - (int)$generalRequest->requestable->duration;

                        EmployeeVacation::find($vacation->id)->update(['vacation_credit' => (int)$vacation->vacation_credit - (int)$generalRequest->requestable->duration]);
                    }
                }

                return response(['status' => 200, "message" => 'success', 'step' => $stepAction->fresh(), 'general_request' => $generalRequest->fresh()]);

            } elseif ($step->status == 2 || $step->status == 0) {
                return response(['status' => 200, "message" => 'request approved or rejected']);

            }
        }

        return response(['status' => 200, "message" => 'success', 'step' => $stepAction ? $stepAction->fresh() : null]);

    }

    public function workMission($mission)
    {
        if(!(($mission->generalRequest->status ==1 ||$mission->generalRequest->status ==2)&& $mission->time_enter ))
            return

        $this->employeeRequest = EmployeeRequest::whereId($mission->id)->first();


        // approve late // checkin ll user when request created

        $statusId = Status::where('type', 'employee-requests')->where('name', 'accepted')->value('id');
        $deniedStatusId = Status::where('type', 'employee-requests')->where('name', 'denied')->value('id');
        $branch = $this->getEmpBranch($this->employeeRequest->employee);

        $userTimeZone = $branch?->time_zone;
        if ($userTimeZone == "" || $userTimeZone == null) {
            $userTimeZone = "Africa/Cairo";
        }

        if ($this->employeeRequest->type == "mission") {

            $empReport = EmployeeReport::where('employee_id', $this->employeeRequest->employee?->id)->whereDate('created_at', Date('Y-m-d', strtotime($this->employeeRequest->created_at)))->first();
            if ($empReport == null) {
                $empReport = new EmployeeReport();
                $empReport->employee_id = $this->employeeRequest->employee?->id;
            }
            $time_from = Carbon::parse($this->employeeRequest->time_from)->timezone($userTimeZone)->format('h:i A');
            $time_to = Carbon::parse($this->employeeRequest->time_to)->timezone($userTimeZone)->format('h:i A');

            $day = Carbon::parse($this->employeeRequest->time_form)->timezone($userTimeZone)->format("D");
//            dd($this->employeeRequest->employee?->getShift());
            $today = ShiftDay::where(['shift_id' => $this->employeeRequest->employee?->getShift()?->id, 'day_name' => $day])->with('shift')->first();;
            if($today==null)
            {
                if ($this->employeeRequest->employee?->has_overtime)
                {
                    $empReport->overtime_hours = round(abs(strtotime($time_to) - strtotime($time_from)) / 3600, 2);
                    $empReport->hourly_value = $this->employeeRequest->employee?->finance?->hourly_value;
                    $empReport->overtime_hour_value = round($this->employeeRequest->employee?->finance?->hourly_value * $today->shift?->overtime_cost, 2);
                    $empReport->currency = $this->employeeRequest->employee?->finance?->currency?->code;
                    $empReport->created_at = $this->employeeRequest->created_at;
                    $empReport->save();
                }
            }
            else{
                $canCheckOutFrom = Carbon::parse($today->end_in)->timezone($userTimeZone)->format('h:i A');
                $lateCheckIn = Carbon::parse($today->late_start_in)->timezone($userTimeZone)->format('h:i A');
                $checkIn = Carbon::parse($today->start_in)->timezone($userTimeZone)->format('h:i A');
                $earlyCheckIn = Carbon::parse($today->early_start_in)->timezone($userTimeZone)->format('h:i A');

                if ($this->employeeRequest->employee?->has_overtime) {
                    if ((strtotime($time_from) > strtotime($canCheckOutFrom) &&
                            strtotime($time_to) > strtotime($canCheckOutFrom))
                        || (strtotime($time_to) < strtotime($earlyCheckIn) &&
                            strtotime($time_from) < strtotime($earlyCheckIn))) {
                        $empReport->overtime_hours = round(abs(strtotime($time_to) - strtotime($time_from)) / 3600, 2);
                    } elseif ((strtotime($time_to) > strtotime($canCheckOutFrom) && (strtotime($time_from) > strtotime($earlyCheckIn) && strtotime($time_from) < strtotime($canCheckOutFrom)))) {
                        $empReport->overtime_hours = round(abs(strtotime($time_to) - strtotime($canCheckOutFrom)) / 3600, 2);
                    } elseif ((strtotime($time_from) < strtotime($earlyCheckIn) && (strtotime($time_to) > strtotime($earlyCheckIn) && strtotime($time_to) < strtotime($canCheckOutFrom)))) {
                        $empReport->overtime_hours = round(abs(strtotime($earlyCheckIn) - strtotime($time_from)) / 3600, 2);
                    }
                    $empReport->overtime_hour_value = round($this->employeeRequest->employee?->finance?->hourly_value * $today->shift?->overtime_cost, 2);
                    // if ($empReport == null) {
                    $empReport->hourly_value = $this->employeeRequest->employee?->finance?->hourly_value;
                    $empReport->overtime_hour_value = round($this->employeeRequest->employee?->finance?->hourly_value * $today->shift?->overtime_cost, 2);
                    $empReport->late_hour_value = round($this->employeeRequest->employee?->finance?->hourly_value * $today->shift?->late_cost, 2);
                    $empReport->currency = $this->employeeRequest->employee?->finance?->currency?->code;
                    $empReport->created_at = $this->employeeRequest->created_at;

                    // }


//            $overTimeHours = $this->employeeRequest->employee?->getOverTimeHours($this->employeeRequest->created_at, $userTimeZone, $dayWork);
//            $WorkHours = $this->employeeRequest->employee?->workHoursInReq($this->employeeRequest->created_at, $userTimeZone, $dayWork);
                    $empReport->save();
                }

            }



        }


    }
}


