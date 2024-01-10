<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;

class StateController extends MainController
{


    public function __construct()
    {
        parent::__construct();
        $this->class = "State";
        $this->table = "states"; 

        $this->middleware("auth");
        $this->middleware('permission:dashboardSetting.state.view', ['only' => ['index']]);
        $this->middleware('permission:dashboardSetting.state.create', ['only' => ['create']]);
        $this->middleware('permission:dashboardSetting.state.edit', ['only' => ['edit']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tree = array_merge($this->tree, [route('admin.settings.dashboard') => 'dashboard-setting']);
        return view('admin.settings.dashboard.state.index', compact('tree'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tree = array_merge($this->tree, [route('admin.settings.dashboard') => 'dashboard-setting',route('admin.settings.dashboard.state.index') => 'states-setting']);
        return view('admin.settings.dashboard.state.create', compact('tree'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tree = array_merge($this->tree, [route('admin.settings.dashboard') => 'dashboard-setting',route('admin.settings.dashboard.cities.index') => 'states-setting']);
        return view('admin.settings.dashboard.state.create', compact('tree','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}


