<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Employee\EmployeeInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DateEndController extends MainController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware("auth");
    }

    public function setting(){

        $tree = array_merge($this->tree, [route('admin.settings.dashboard') => 'dashboard-setting']);

        return view('admin.settings.dashboard.date-end.index',compact('tree'));
    }

    public function store(){

        $tree = array_merge($this->tree, [route('admin.settings.dashboard') => 'dashboard-setting']);

        return view('admin.settings.dashboard.date-end.create',compact('tree'));
    }

}
