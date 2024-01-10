<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Traits\AdminHelperTrait;
use App\Traits\ApiResponseTrait;
use App\Traits\ResponseMessageTrait;
use App\Traits\NotifiTrait;
class ApiController extends Controller
{
    use ApiTrait,AdminHelperTrait,NotifiTrait;
    protected  $service, $class, $table, $limit = 10,$orderBy = 'created_at',$orderDesc = true ;

    protected $admin_phones = ["0538500542","0545550161"];
    public function __construct()
    {

    }

}
