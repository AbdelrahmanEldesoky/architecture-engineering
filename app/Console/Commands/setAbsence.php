<?php

namespace App\Console\Commands;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeReport;
use App\Models\Hr\Shift;
use App\Models\Hr\ShiftDay;
use App\Traits\AttendanceTrait;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Console\Command;

class setAbsence extends Command
{
    use AttendanceTrait, SmsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-absence';

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
//        $employees = Employee::whereHas('attendances', function ($query) {
//            $query->where('date', '<>', Carbon::now($query->workAt->department->management->branch->time_zone)->format('Y-m-d'));
//        })->where(function ($builder){
//            $builder->whereHas('shift',function ($query)use($builder){
//                $query->whereHas('days',function ($q)use ($query,$builder){
//                    $q->where(["shift_id" => $query->id, "day_name" => Carbon::now($builder->workAt->department->management->branch->time_zone)->format('D')]);
//                });
//            })->orWhereHas('workAt',function ($query){
//                $query->whereHas('department',function ($q){
//                   $q->whereHas('management',function ($management){
//                      $management->whereHas('branch',function ($branch){
//                         $branch->whereHas('shift',function ($shift){
//                            $shift->
//                         });
//                      });
//                   });
//                });
//            });

//        })->get();
//        $employees = Employee::query()->whereDoesntHave('reports',function ($query){
//            $query->where('created_at',Carbon::now());
//        })->get();

            $employees = Employee::query()->whereDoesntHave('reports', function ($query) {
                $query->where('created_at', Carbon::now() ) ;
            })->get();

            foreach ($employees as $employee) {

                try {
                    $branch = $this->getEmpBranch($employee);
                    $shift = $this->getEmpShift($employee);
                } catch (Exception $e) {
                    continue;
                }


                if (!$shift instanceof Shift) {
                    $this->info('Error in Shift');
                    continue;
                }
                // end check employee, branch and shift

                // get user time zone
                $userTimeZone = $branch->timezone;
                if ($userTimeZone == "") {
                    $userTimeZone = "Africa/Cairo";
                }
                // end timezone

                // get today and check if employee has work today
                $day = Carbon::now()->format('D');

                $today = ShiftDay::where(["shift_id" => $shift->id, "day_name" => $day])->first();
                if (empty($today)) {

                    $this->info('holiday Shift');
                    $report =EmployeeReport::create(['employee_id' => $employee->id, 'user_id' => $employee->user->id,'shift_working_hours'=>$employee->finance?->work_hours,'hourly_value'=>$employee->finance?->hourly_value,'created_at'=>Carbon::now()]);
                    $report->created_at =Carbon::now() ;
                    $report->save();


                }


            }


        }

}
