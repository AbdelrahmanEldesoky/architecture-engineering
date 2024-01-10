<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;

class EmployeeRelation extends MainController
{
    public function getMyVacation()
    {
        return response(['status'=>200 , 'message'=>'success','vacations'=> auth('api')->user()->employee->vacation]);
    }
}
