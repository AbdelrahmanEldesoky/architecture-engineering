<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\AttendanceController;
use App\Models\Attendance;
use App\Models\Employee\Employee;
use App\Models\Employee\Offline;
use App\Models\Hr\ShiftDay;
use App\Notifications\MainNotification;
use App\Services\FCM\FCMService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOfflineEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-offline-employee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check offline model for employees to checkout';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $offlines = Offline::with(['attendance' => fn($q) => $q->with('shift')])->whereDate('created_at', Carbon::today())->get();

        foreach ($offlines as $offline) {
            if ($offline->attendance->check_out) {
                $offline->delete();
            } else {
                $this->info('Offline models checked and Checkout successfully.');
                $minutes = $offline->attendance->shift->offline ?? 1;
                $minutesAgo = Carbon::now()->subMinutes($minutes);
                if (strtotime($offline->time) <= strtotime($minutesAgo)) {
                    (new \App\Http\Controllers\Api\Employee\AttendanceController())->checkout( Carbon::parse($offline->time)->format('h:i A'), $offline->attendance, Employee::query()->find($offline->attendance->employee_id));
                    Log::info('Employee ID' .$offline->attendance->employee_id .' CheckOut By Offline For ' . $offline->reason);
                    $offline->delete();
                }
            }
        }

//        $attendances = Attendance::with(['employee' => fn($q) => $q->with('user'), 'shift'])->whereNull('check_out')
//            ->whereDate('created_at', Carbon::today())->get();
//
//        foreach ($attendances as $attendance) {
//            $today = ShiftDay::where(["shift_id" => $attendance->shift->id, "day_name" => Carbon::now()->format('D')])->first();
//
//            $diffInMinutes = now('Africa/Cairo')->diffInMinutes($today->end_in, false);
//            if (!$attendance->checkout) {
//                if ($diffInMinutes < 0) {     // auto check out
//                    $attendance->checkout = Carbon::parse($attendance->checkout)->format('h:i A');
//                    $attendance->save();
//                } elseif ($diffInMinutes <= 13 && $diffInMinutes >= 18) { // send notification to users
//                    $data = [];
//                    $data['from'] = config('app.name');
//                    $data['message'] = 'إقترب وقت اﻹنتهاء من الدوام ,يرجى اﻹستعداد للإنصراف .';
//                    $attendance->employee->user->notify(new MainNotification($data));
////                $fcm = new FCMService();
////                $fcm->sendNotification([$attendance->employee?->user?->id], "إقترب وقت اﻹنتهاء من الدوام"
////                    , $data['message']
////                    , null, null, null, "users");
//                }
//            }
//
//            $this->info($diffInMinutes);
//            $this->info('Offline models checked and Checkout successfully.');
//        }
    }
}
