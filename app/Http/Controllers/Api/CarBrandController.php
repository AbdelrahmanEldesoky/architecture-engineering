<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\CarBrand;
use Illuminate\Http\Request;


use App\Http\Resources\AttendanceResource;

class CarBrandController extends ApiController
{


    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {

        $cars = CarBrand::where('name','like', '%'.$request->name.'%')->get(['id','name']);


        return response()->json([
            'status'=>200 ,
             'message'=>'success',
             'data'=> $cars  
            ]);
    }

}
