<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;

class DepartmentTreeController extends MainController
{
    public function index(Request $request) {

        return view('admin.departments.tree')->with(['branch_id'=>$request->branch_id,'management_id'=>$request->management_id,'tree'=>$this->tree]);
    }

    public function detailsTree(Request $request) {
        return view('admin.departments.tree_details')->with(['management_id'=>$request->management_id,'department_id' => $request->department_id,'parent_type'=>
            $request->parent_type,'tree'=>$this->tree]);
    }
}
