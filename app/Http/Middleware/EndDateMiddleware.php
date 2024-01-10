<?php

namespace App\Http\Middleware;

use App\Models\Employee\EmployeeInfo;
use App\Models\Employee\EmployeeTrack;
use App\Models\Renge;
use App\Models\SmsEndDate;
use App\Models\User;
use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Traits\SmsTrait;
use App\Traits\AttendanceTrait;
class EndDateMiddleware
{
  use AttendanceTrait,SmsTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      $date = Carbon::today();
      $date_today = $date->format('Y-m-d');
      $renge = Renge::first();
      $date_in_count_days = $date->addDays($renge?->renge_count)->format('Y-m-d');


      $employeeInfos = EmployeeInfo::where(function ($query) use ($date_today, $date_in_count_days) {
                                      $query
                                      // ->where('end_id_number', '>', $date_today)
                                          ->where('end_id_number', '<=', $date_in_count_days);
                                      })->orWhere(function ($query) use ($date_today, $date_in_count_days) {
                                      $query
                                      // ->where('end_medical_insurance', '>', $date_today)
                                          ->where('end_medical_insurance', '<=', $date_in_count_days);
                                      })->orWhere(function ($query) use ($date_today, $date_in_count_days) {
                                      $query
                                      // ->where('end_saudi_authority', '>', $date_today)
                                          ->where('end_saudi_authority', '<=', $date_in_count_days);
                                      })->with('employee' , 'employee.workAt')
                                      ->get();

      foreach($employeeInfos as $employeeInfo){

          $sms_end_id_number = SmsEndDate::where('employee_id',$employeeInfo->employee_id)->where('type' , 'end_id_number')->where('type_date',$employeeInfo->end_id_number)->count();
          $sms_end_medical_insurance = SmsEndDate::where('employee_id',$employeeInfo->employee_id)->where('type' , 'end_medical_insurance')->where('type_date',$employeeInfo->end_medical_insurance)->count();
          $sms_end_saudi_authority = SmsEndDate::where('employee_id',$employeeInfo->employee_id)->where('type' , 'end_saudi_authority')->where('type_date',$employeeInfo->end_saudi_authority)->count();

          if($employeeInfo->end_id_number!=null && $sms_end_id_number == 0){
              $SmsEndDate = SmsEndDate::create([
                                  'employee_id' => $employeeInfo->employee_id,
                                  'type' => 'end_id_number',
                                  'type_date'=> $employeeInfo->end_id_number,
                                  'is_send' => 1
                              ]);
              $message = "سيتم انهاء الهوية الشخصية الخاص بك في تاريخ " . " ".$employeeInfo->end_id_number ;
              if($employeeInfo->employee?->phone != null){
              $this->sendSmsMessageMora($employeeInfo->employee->phone,$message);
                            }
            }

          if($employeeInfo->end_medical_insurance!=null && $sms_end_medical_insurance == 0){
              $SmsEndDate = SmsEndDate::create([
                                  'employee_id' => $employeeInfo->employee_id,
                                  'type' => 'end_medical_insurance',
                                  'type_date'=> $employeeInfo->end_medical_insurance,
                                  'is_send' => 1
                              ]);
              $message = "سيتم انهاء التأمين الصحي الخاص بك في تاريخ " . " ".$employeeInfo->end_medical_insurance ;
              if($employeeInfo->employee?->phone != null){
                $this->sendSmsMessageMora($employeeInfo->employee->phone,$message);
                }
          }

          if($employeeInfo->end_saudi_authority!=null && $sms_end_saudi_authority == 0){
              $SmsEndDate = SmsEndDate::create([
                                  'employee_id' => $employeeInfo->employee_id,
                                  'type' => 'end_saudi_authority',
                                  'type_date'=> $employeeInfo->end_saudi_authority,
              ]);
              $message = "سيتم انهاء الهيئة السعودية الخاص بك في تاريخ " . " ".$employeeInfo->end_saudi_authority ;
              if($employeeInfo->employee?->phone != null){
                $this->sendSmsMessageMora($employeeInfo->employee->phone,$message);
                }
          }
      }


        return $next($request);
    }
}
