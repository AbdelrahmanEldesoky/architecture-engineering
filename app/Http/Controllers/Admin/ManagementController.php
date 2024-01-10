<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;

class ManagementController extends MainController
{
    public function __construct()
    {
        parent::__construct() ;
        $this->class = 'management' ;
        $this->table = 'managements' ;
        $this->middleware('auth');
        $this->middleware('permission:managements.view', ['only' => ['index', 'show']]);
        $this->middleware('permission:managements.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:managements.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:managements.delete', ['only' => ['destroy']]);
    }
    public function index(Request $request) {

        return view('admin.managements.index')->with(['branch_id'=>$request->branch_id,'tree'=>$this->tree]);
    }

    /**
     * @param Request $request
     * @props type 1 branch_id   2 management_id  3 department_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */

    public function create(Request $request) {
        $tree = array_merge($this->tree, [route('admin.managements.index') => 'managements']);

        return view('admin.managements.create')->with(['branch_id'=>$request->branch_id,'management_id'=>$request->management_id,'parent_type'=>$request->parent_type,'tree'=>$this->tree]);
    }

    public function edit(Request $request,$management) {
        $tree = array_merge($this->tree, [route('admin.managements.index') => 'managements']);

        return view('admin.managements.edit')->with(['branch_id'=>$request->branch_id,'management_id'=>$management,'parent_type'=>$request->parent_type,'tree'=>$this->tree]);
    }
}
