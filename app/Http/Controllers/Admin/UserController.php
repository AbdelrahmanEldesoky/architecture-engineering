<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
class UserController extends MainController
{
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('permission:users.view', ['only' => ['index', 'show']]);
        $this->middleware('permission:users.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request) {
        // $service = new UserService();
        
        // $usersQuery = $service->search($request->search);
        
        // $users = $usersQuery->with(['employee','roles'])->get();
                            
         
                   

        // return $users;
        return view('admin.users.index')->with('tree' , $this->tree);
    }

    public function create() {

        return view('admin.users.form')->with('tree',$this->tree);
    }

    public function edit($user_id) {
        $tree = array_merge($this->tree, [route('admin.users.index') => 'users']);
        return view('admin.users.form')->with(['user_id'=> $user_id,'tree'=>$tree]);
    }
}
