<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hr\Branch;
use App\Models\Hr\Department;
use App\Models\Hr\Management;
use Illuminate\Http\Request;

class WorkAtController extends Controller
{
    public function getAllDepartments()
    {
        return response(['departments'=>Department::all()]);
    }

    public function getAllBranches()
    {
        return response(['branches'=>Branch::all()]);

    }

    public function getAllManagements()
    {
        return response(['managements'=>Management::all()]);

    }
}
