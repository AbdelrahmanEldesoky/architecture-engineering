<?php

namespace App\Http\Controllers\Admin\Api;

use App\Helpers\RequestHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Requests\ContractRequest;
use App\Http\Requests\ContractTaskRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Broker;
use App\Models\Client;
use App\Models\Contract\ContractTask;
use App\Models\Contract\ContractType;
use App\Models\Hr\Branch;
use App\Models\Hr\Management;
use App\Models\Status;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Projects\Entities\Contract;
use Modules\Projects\Entities\ContractPayment;
use Validator;
class ContractTaskController extends MainController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        // parent::__construct();
        // $this->class = "contract";
        // $this->table = "contracts";
        // $this->middleware('auth');
        // $this->middleware('permission:clients.view', ['only' => ['index', 'show']]);
        // $this->middleware('permission:clients.create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:clients.edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:clients.delete', ['only' => ['destroy']]);
    }

    public function index($contractId)
    {
        $contract_task =  ContractTask::where('contract_id',$contractId)->with(['employees'])->get();
            
        return response()->json([
            'success' => true,
            'msg' => '',
            'data' => $contract_task
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContractTaskRequest $request)
    {
        $validated = $request->all(); 

        $contract_task =  ContractTask::create($validated);

        return response()->json([
            'success' => true,
            'msg' => 'تم تسجيل مهمة عمل جديدة',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */

    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contract = ContractTask::findOrFail($id);

        $contract->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'تم التعديل بنجاح'   
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contract = ContractTask::findOrFail($id);   
            
        $contract->delete(); 
        
        return response()->json([
            'success' => true,
            'message' => 'تم الحذف بنجاح'   
        ]);
    }

}
