<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeTrackRequest;
use App\Http\Resources\Employee\EmployeeRequestResource;
use App\Models\Attendance;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeReport;
use App\Models\Employee\EmployeeRequest;
use App\Models\Employee\EmployeeTrack;
use App\Models\Hr\ShiftDay;
use App\Models\Status;
use App\Models\User;
use App\Notifications\MainNotification;
use App\Traits\AttendanceTrait;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class EmployeeRequestsController extends ApiController
{
    use AttendanceTrait,SmsTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function requests(Request $request)
    {
        $user = auth('api')->user();
        $id = Employee::where('user_id', auth('api')->id())->value('id');
        $requests = EmployeeRequest::with('status:id,name')->where('employee_id', $id)->get();
//        return $requests ;
        return $this->successResponse(EmployeeRequestResource::collection($requests));
    }

    public function createRequest(Request $request)
    {
        $rules = [
            'type' => 'required|string|in:remote,mission,overtime,late,checkoutPermission',
            'name' => 'required_if:type,mission|string',
            'responsible_employee_id' => 'required|int|exists:employees,id',
            'time_from' => 'required_if:type,mission|date',
            'time_to' => 'required_if:type,mission|date',
            'latitude' => 'required_if:type,mission|numeric',
            'longitude' => 'required_if:type,mission|numeric',
            'address' => 'required_if:type,mission|string',
        ];

        $validator = validator()->make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $userTimeZone = timezone($request->ip());
        if ($userTimeZone == "") {
            $userTimeZone = "Africa/Cairo";
        }
        $input['time_zone'] = $userTimeZone;

        $input = $request->all();
        $employee = Employee::draft(0)->where('user_id', auth('api')->id())->with('workAt')->first();
        if (!$employee) {
            return $this->errorResponse('Employee not found');
        }


        if ($input['type'] != 'mission') {
            $input['name'] = __('names.' . $input['type']);
//          $input['responsible'] =    auth('api')->user()->name;// until migrate
        }

        $statusId = Status::where('type', 'employee-requests')->where('name', 'pending')->value('id');

        $DeniedstatusId = Status::where('type', 'employee-requests')->where('name', 'denied')->value('id');
        $oldReq = EmployeeRequest::where(['employee_id' => $employee->id, 'type' => $input['type'], ['status_id', '<>', $DeniedstatusId]])->whereDate('created_at', Carbon::today())->first();
        if (!empty($oldReq)) {
            return $this->errorResponse('Request Already Created Before', 500);
        }
        if ($input['type'] == 'mission') {
//            return $this->errorResponse($input, 500);
            //utc()->format
            $input['time_from'] = Carbon::parse($input['time_from'], $userTimeZone)->utc()->format('Y-m-d H:i:s');
            $input['time_to'] = Carbon::parse($input['time_to'], $userTimeZone)->utc()->format('Y-m-d H:i:s');

            $server_time = Carbon::now();
            if($server_time > $input['time_from'] || $input['time_to'] < $input['time_from']){
                return $this->errorResponse(__('Time Not Valid'));
            }

            // $input['time_from'] = Date('Y-m-d h:i:s', strtotime($input['time_from']));
            // $input['time_to'] = Date('Y-m-d h:i:s', strtotime($input['time_to']));

            $dest = $input['latitude'] . ',' . $input['longitude'];
            $latAndLong = $this->getLatAndLong($employee);

            if (gettype($latAndLong) != 'array') {
                return $this->errorResponse('No Location Detected For Employee', 500);
            }

            $origin = $latAndLong['lat'] . ',' . $latAndLong['long'];


            $timeBetween = $this->getTimeBetweenTwoLocations($dest, $origin);
            if ($timeBetween == "Error") {
                return $this->errorResponse('Cannot Detect Time To Destnation', 500);
            }

            $input['time_valid_to'] = date('Y-m-d H:i:s', strtotime($input['time_from']) + $timeBetween * 2);
            $input['time_valid_in_seconds'] = $timeBetween * 2;

        } else if ($input['type'] == 'overtime') {
            $input['time_from'] = Date('Y-m-d h:i:s');
            $input['time_to'] = null;
        } else {
            $input['time_from'] = Date('Y-m-d h:i:s');
            $input['time_to'] = Date('Y-m-d h:i:s', strtotime("+10 hours"));
        }
        $input['from'] = 'application';
        $input['employee_id'] = $employee->id;
        $input['status_id'] = $statusId;
        EmployeeRequest::create(array_merge($input,
            [
                'responsible_employee_id'=>Employee::query()->where('id',$request->responsible_employee_id)->first()?->id,
                'responsible'=>Employee::query()->where('id',$request->responsible_employee_id)->first()?->name,
            ]));
        $permissions = ['attendance.requests.changeStatus', 'attendance.requests.view'];
        $users = User::whereHas('permissions', fn($q) => $q->whereIn('name', $permissions))
            ->orWhereHas('roles', fn($q) => $q->whereHas('permissions', fn($q) => $q->where('name', $permissions)))->get();
        $data = [];
        $data['from'] = config('app.name');
        $data['message'] = 'الموظف ' . $employee->first_name . ' أرسل طلب ' . __('names.' . $input['type']);
        $data['url'] = route('admin.attendance.requests.index');
        Notification::send($users, new MainNotification($data));
        // $this->sendSmsMessageMora($this->admin_phones,$data['message']);
        return $this->successResponse('Request Received Successfully ');
    }
    public function editTimeEnterAndOut(EmployeeRequest $employeeRequest, Request $request)
    {
//        return $request->time_enter;
        if ($request->has('time_enter'))
            $employeeRequest->update(['time_enter' => Carbon::parse($request->time_enter)->format('Y-m-d H:i:s')]);
        if ($request->has('time_out'))
            $employeeRequest->update(['time_out' => Carbon::parse($request->time_out)->format('Y-m-d H:i:s')]);
        (new GeneralRequestController)->workMission($employeeRequest);
        return $this->successResponse($employeeRequest->fresh());
    }

    public function employees(Request $request)
    {
        $employees = Employee::draft(0)->get(['id', 'first_name', 'second_name', 'last_name']);
        $employees->map(fn($emp) => $emp->full_name = $emp->first_name . ' ' . $emp->second_name . ' ' . $emp->last_name);
        return $this->successResponse($employees);
    }

    public function track(EmployeeTrackRequest $request)
    {
        $employee = Employee::where('user_id', auth()->user()->id)->first();
        if ($employee == null)
            return $this->errorResponse("employee not found");
        $attendance = Attendance::where('employee_id', $employee->id)->whereDate('created_at', Carbon::today())->first();

            return $this->successResponse(EmployeeTrack::updateOrCreate(['employee_id' => $employee->id], array_merge($request->validated(),
                ['last_seen' => Carbon::now(), 'employee_id' => $employee->id])));

    }


}
