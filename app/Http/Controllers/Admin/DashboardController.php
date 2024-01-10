<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Employee\Employee;
use App\Models\Hr\Department;
use App\Models\Hr\Management;
use App\Notifications\MainNotification;
use Illuminate\Http\Request;
use App\Models\Employee\EmployeeInfo;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends MainController
{
   public function __construct()
   {
       parent::__construct();
//       $this->middleware('permission:admin');
   }

   public function dashboard()
   {
    $date = Carbon::today();
    $date_day = $date->format('d-m-Y');
    $date_today = $date->format('m-d');

    $count_bitrhday = EmployeeInfo::whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", $date_today)->count();

    $Employee_birthday =  Employee::with(['info','employmentData','employmentData.jobName','employmentData.jobType','workAt'])->whereHas('info', function ($q) use($date_today) {
                                $q->whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", $date_today);})->get();
    

    $query = Employee::query()->with(['workAt'])->draft(0) ;
                                 
    $query->whereHas("workAt", fn($q) => $q->where('workable_id', 39)->where('workable_type', 'branches'));
    $managementIds = Management::where('branch_id',39)->pluck('id')->toArray();
    $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $managementIds)->where('workable_type', 'managements'));
    $departmentIds = Department::whereIn('management_id',$managementIds)->pluck('id')->toArray();
    $query->orWhereHas("workAt", fn($q) => $q->whereIn('workable_id', $departmentIds)->where('workable_type', 'departments'));
    $employee_show = $query->where('email',auth()->user()->email)->count() ;

    return view('admin.dashboard', compact('date_day','Employee_birthday','count_bitrhday','employee_show'));


   }

    public function notifications()
    {
        $user = auth('web')->user();
//        $data = array();
//        $data['from'] = 'system';
//        $data['message'] = 'welcome to admin panel';
//        $data['url'] = url('/');
//        $user->notify(new MainNotification($data));
//        $user->notifications()->read();
        return view('admin.notifications')->with(['tree'=>$this->tree]);
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx',\Maatwebsite\Excel\Excel::XLSX);
    }
}
