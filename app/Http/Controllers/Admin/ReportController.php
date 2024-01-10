<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeReport;
use App\Models\Employee\EmployeeTrack;
use App\Models\Hr\Department;
use App\Models\Hr\Management;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
class ReportController extends MainController
{
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
     
    }
    public function employees(Request $request){
        $tree = $this->tree ;
        
        return view('admin.employees.report')->with('tree', $tree);
    }
} 
