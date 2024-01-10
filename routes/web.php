<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/auto', function () {

});

Route::get("test2", function (Request $request) {

//        $timezone = timezone($request->ip());
//        $date = Carbon::now();
//        $timezoneServerDate = $date->format("H:i A");
//        $date->timezone($timezone);
//        $formattedDate = $date->format('H:i A');
//        echo  '<p>Your IP : ' . timezone($request->ip()) . '<\p>';
//        echo '<br>';
//        echo '<p>Your Date in time zone : ' . $formattedDate  . '<\p>';
//        echo '<br>';
//        echo '<p>Server Date with his timezone : ' . $timezoneServerDate  . '<\p>';
    // $id = 1;
    // $employee = \App\Models\Employee\Employee::with(['employmentData' => fn($q) => $q->with('jobName'), 'info', 'workAt'])->whereId($id)->first();
    // $data = 'إسم الموظف : ' . $employee->first_name . ' ' . $employee->second_name . ' ' . $employee->last_name
    //     . "\n"
    //     . 'رقم الهوية : ' . $employee->info->id_number
    //     . "\n"
    //     . 'الوظيفة : ' . $employee->employmentData->jobName->name
    //     . "\n"
    //     . 'الفرع : ' . $employee->scopeBranchName()
    //     . "\n"
    //     . 'اﻹدارة : ' . $employee->scopeManagementName()
    //     ."\n"
    //     . 'القسم : ' . $employee->workAt->workable->name;
    // return QrCode::generate(utf8_encode($data));

    //  $employee = \App\Models\Employee\Employee::whereId(64)->first();
    //  return $employee->getEmpFinanceReports('03-10-2023','Africa/Cairo');

    // $allAttendances = \App\Models\Attendance::with(['shift.days'],'employee')->get();

    // foreach($allAttendances as $attendance) {
    //     $report = \App\Models\Employee\EmployeeReport::where('employee_id', $attendance->employee_id)->whereDate('created_at', Date('Y-m-d', strtotime($attendance->created_at)))->first();

    //     if(empty($report)) {

    //         $day = $attendance->shift->days->where('day_name', Date("D", strtotime($attendance->created_at)))->first();
    //         if(!empty($day)) {
    //         $totalWorkHours = round(abs(strtotime(Date('h:i A', strtotime($day->end_in))) - strtotime(Date('h:i A', strtotime($day->start_in)))) / 3600, 2);
    //         $lateHours = 0;

    //         if(strtotime(Date('h:i A', strtotime($attendance->check_in))) > strtotime(Date('h:i A', strtotime($day->late_start_in)))) {
    //             $lateHours = round(abs(strtotime($attendance->check_in) - strtotime(Date('h:i A', strtotime($day->start_in))))  / 3600 , 2);
    //         }


    //         $ActualWorkHours = round(abs(strtotime($attendance->check_out) - strtotime($attendance->check_in)) / 3600 , 2);
    //         if($ActualWorkHours > $totalWorkHours) {
    //             $ActualWorkHours = $totalWorkHours;
    //         }
    //         if(empty($attendance->check_out)) {
    //             $ActualWorkHours = 0;
    //         }


    //         $createReport = new \App\Models\Employee\EmployeeReport([
    //             'employee_id' => $attendance->employee_id,
    //             'start_in' => Date('h:i A', strtotime($day->start_in)),
    //             'end_in' => Date('h:i A', strtotime($day->end_in)),
    //             'check_in' => $attendance->check_in,
    //             'check_out' => $attendance->check_out,
    //             'work_hours' =>  $ActualWorkHours,
    //             'late_hours' => $lateHours,
    //             'hourly_value' => $attendance->employee?->finance?->hourly_value,
    //             'late_hour_value' => $attendance->employee?->finance?->hourly_value * $attendance->shift?->late_cost,
    //             'overtime_hours' => 0,
    //             'overtime_hour_value' => $attendance->employee?->finance?->hourly_value * $attendance->shift?->overtime_cost,

    //         ]);
    //         $createReport->save();
    //         $createReport->total = round(($createReport->work_hours * $createReport->hourly_value) - ($createReport->late_hours * $createReport->late_hour_value), 2);
    //         $createReport->created_at = $attendance->created_at;
    //         $createReport->updated_at = $attendance->updated_at;
    //         $createReport->save();
    //         }
    //     }
    // }
    // return 'success';
});


// Route::get('/test-attend',function() {
//     $emp = \App\Models\Emplouee\Employee::whereId(80)->first();
//     return $emp->getShift();
// });

Auth::routes();

Route::get('/login', function () {
    return redirect('/admin/login');
});

Route::group([
    'middleware' => ['auth:web'],
    'namespace' => 'Auth',
], function () {

//    Route::get('/', function () {
//        return redirect('/admin');
//    });

    Route::post('temp/process/', [\App\Http\Controllers\FileUploadController::class, 'process'])->name('upload.process');
    Route::delete('temp/delete', [\App\Http\Controllers\FileUploadController::class, 'delete'])->name('upload.delete');
//    Route::get('/download/{path}', [\App\Http\Controllers\FileUploadController::class, 'download'])->name('download');

    Route::post('download/', [\App\Http\Controllers\FileUploadController::class, 'download'])->name('download');

//    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

});


Route::get('{any?}', function ($any=null) {
     $str=substr($any,0,6);
    if ($str == 'react'|| $str == 'react/')
        return view('app');
    else
        return redirect('/admin');
})->where("any", ".*")->where("any", "^(?!api).*$");

