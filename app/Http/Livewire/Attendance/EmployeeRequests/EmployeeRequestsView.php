<?php

namespace App\Http\Livewire\Attendance\EmployeeRequests;

use App\Http\Livewire\Basic\Modal;
use App\Models\Attendance;
use App\Models\Employee\EmployeeReport;
use App\Models\Employee\EmployeeRequest;
use App\Models\Hr\Shift;
use App\Models\Hr\ShiftDay;
use App\Models\Status;
use App\Models\User;
use App\Notifications\MainNotification;
use App\Services\FCM\FCMService;
use App\Traits\AttendanceTrait;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Http\Request;

class EmployeeRequestsView extends Modal
{

    use AttendanceTrait,SmsTrait;

    protected $rules = [
        'employeeRequest.status_id' => 'required|exists:statuses,id',
        'employeeRequest.response' => 'nullable|string'
    ];
    protected $listeners = ['updateLatAndLong' => 'updateLatAndLong'];
    public $employeeRequest;
    public $employeeRequest_id;
    public $statues;
    public $status;
    public $Oldresponse;
    public $deniedId;
    public $timezone = "Africa/Cairo";

    public function mount(Request $request, $id)
    {
        $this->employeeRequest_id = $id;
        $this->employeeRequest = EmployeeRequest::with('status', 'employee')->whereId($id)->first();
        if (!$this->employeeRequest) {
            abort(404);
        }
        $this->statues = Status::where("type", 'employee-requests')->whereIn('name', ['accepted', 'denied'])->pluck('name', 'id')->toArray();
        $this->Oldresponse = $this->employeeRequest->response;
        foreach ($this->statues as $key => $name) {
            if ($name === 'pending') {
                $this->deniedId = $key;
                break;
            }
        }

        $timezone = timezone($request->ip());
        if ($timezone != "") {
            $this->timezone = $timezone;
        }
//        dd(    $this->employeeRequest->status);
//        dd($this->deniedId);
    }

    public function render()
    {
        return view('livewire.attendance.employee-requests.employee-requests-view');
    }


    public function updatedEmployeeRequestStatusId($data)
    {
        if ($data == $this->deniedId) {
            $this->employeeRequest->response = "";
        } else {
            $this->employeeRequest->response = $this->Oldresponse;
        }
    }

    public function updateLatAndLong($data)
    {
        $coo = explode('-', $data);
        $this->employeeRequest->latitude = $coo[0];
        $this->employeeRequest->longitude = $coo[1];
        $this->employeeRequest->save();
        $this->dispatchBrowserEvent('toastr',
            ['type' => 'success', 'message' => __('message.created', ['model' => __('names.location')])]);

        //$this->dispatchBrowserEvent('initMap');
    }

    public function save(Request $request)
    {
//        dd($request);
        $validated = $this->validate();
        $this->employeeRequest->save();


        $this->dispatchBrowserEvent('toastr',
            ['type' => 'success', 'message' => __('message.updated', ['model' => __('names.employee-request')])]);
//        $statusId = Status::where("type",'tickets')->where('name','accepted')->value('id');
        $this->employeeRequest = EmployeeRequest::whereId($this->employeeRequest_id)->first();
        $branch=$this->getEmpBranch($this->employeeRequest->employee);


        // approve late // checkin ll user when request created

        $statusId = Status::where('type', 'employee-requests')->where('name', 'accepted')->value('id');
        $deniedStatusId = Status::where('type', 'employee-requests')->where('name', 'denied')->value('id');

        $userTimeZone = $branch->time_zone;
        if ($userTimeZone == "" ) {
            $userTimeZone = "Africa/Cairo";
        }

        if ($this->employeeRequest->type == "mission") {


            $day = Carbon::parse($this->employeeRequest->time_formtime_form)->timezone($userTimeZone)->format("D");
//            dd($this->employeeRequest->employee?->getShift());
            $today = ShiftDay::where(['shift_id' => $this->employeeRequest->employee?->getShift()?->id, 'day_name' => $day])->with('shift')->first();;

            $canCheckOutFrom = Carbon::parse($today->end_in)->timezone($userTimeZone)->format('h:i A');
            $lateCheckIn = Carbon::parse($today->late_start_in)->timezone($userTimeZone)->format('h:i A');
            $checkIn = Carbon::parse($today->start_in)->timezone($userTimeZone)->format('h:i A');
            $earlyCheckIn = Carbon::parse($today->early_start_in)->timezone($userTimeZone)->format('h:i A');

            $time_from = Carbon::parse($this->employeeRequest->time_from)->timezone($userTimeZone)->format('h:i A');
            $time_to = Carbon::parse($this->employeeRequest->time_to)->timezone($userTimeZone)->format('h:i A');
            $empReport = EmployeeReport::where('employee_id', $this->employeeRequest->employee?->id)->whereDate('created_at', Date('Y-m-d', strtotime($this->employeeRequest->created_at)))->first();
            if ($empReport == null) {
                $empReport = new EmployeeReport();
                $empReport->employee_id = $this->employeeRequest->employee?->id;
            }
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

            if (Status::find($this->employeeRequest->status_id)->name != 'accepted' ){
                $empReport->overtime_hours = 0;
            }
//            $overTimeHours = $this->employeeRequest->employee?->getOverTimeHours($this->employeeRequest->created_at, $userTimeZone, $dayWork);
//            $WorkHours = $this->employeeRequest->employee?->workHoursInReq($this->employeeRequest->created_at, $userTimeZone, $dayWork);
            $empReport->save();
            }
        }


        $user = User::whereHas('employee', fn($q) => $q->whereHas('requests', fn($q) => $q->where('id', $this->employeeRequest_id)))->first();
        $data = [];
        $data['from'] = config('app.name');
        $data['message'] = 'تم الرد علي طلبك ' . ",تم تغيير حالة طلبك إلي " .
            __('names.' . $this->employeeRequest->status?->name);
        $user->notify(new MainNotification($data));
        $data['message_sms'] = 'الموظف ' . $this->employeeRequest->employee?->first_name .'تم الرد علي طلب ' . ",تم تغيير حالة طلب إلي " .
            __('names.' . $this->employeeRequest->status?->name);
//        $this->sendSmsMessageMora(["0538500542"],$data['message_sms']);
        $fcm = new FCMService();
        $fcm->sendNotification([$user->id], "تم الرد علي طلب " .
            $this->employeeRequest->name, $data['message']
            , null, null, null, "users");
    }

}
