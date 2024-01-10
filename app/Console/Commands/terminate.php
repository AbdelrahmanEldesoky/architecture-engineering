<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\Employee\AttendanceController;
use App\Models\Employee\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;

class terminate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:terminate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $employees = Employee::whereHas('attendance', function ($query) {
            $query->whereDate('created_at', Carbon::today())->where('check_out', null);
        })->with('employeeTrack')->get();

        foreach ($employees as $employee) {
            $this->info('Offline models checked and Checkout succe00ssfully.');
            if (!empty($employee->employeeTrack)&&Carbon::parse($employee->employeeTrack?->last_seen)->diffInMinutes(Carbon::now()) >= 30)
//                $employee->attendance->check_out = Carbon::parse($employee->employeeTrack->last_seen )->format('h:i A');
                (new AttendanceController())->checkout(Carbon::parse($employee->employeeTrack->last_seen)->format('h:i A'), $employee->attendance, $employee);
            $employee->attendance->save();
        }

    }
}
