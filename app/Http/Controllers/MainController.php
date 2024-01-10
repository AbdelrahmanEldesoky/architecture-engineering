<?php

namespace App\Http\Controllers;

use App\Services\FCM\FCMService;
use Illuminate\Http\Request;

class MainController extends Controller
{
    protected  $service,$lang, $class, $table, $limit = 10 ;
    protected $fcmService;
    protected array $tree;
    public function __construct()
    {
        $this->fcmService = new FCMService();
        $this->tree = [url('/admin') => 'dashboard'];

    }

    public function getEmployeeForUser()
    {
        return auth('api')->user()->employee;
    }
}
