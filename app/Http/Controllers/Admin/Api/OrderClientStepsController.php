<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\MainController;
use App\Http\Requests\OrderClientStepsRequest;
use App\Models\Contract\ContractForm;
use App\Models\Contract\OrderStep;
use App\Models\Contract\OrderClient;
use App\Models\Contract\OrderStepForm;
use App\Models\Employee\Employee;
use App\Models\Hr\Department;
use App\Models\Status;
use App\Models\Hr\Management;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Contract\TypeOrder;

use Validator;
class OrderClientStepsController extends MainController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->class = "contract";
    //     $this->table = "contracts";
    //     $this->middleware('auth');
    //     $this->middleware('permission:clients.view', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:clients.create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:clients.edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:clients.delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    { 
        $order_step =  OrderStep::with(['form','department','employees'])->get();
            
        return response()->json([
            'success' => true,
            'msg' => '',
            'data' => $order_step
        ]);
    }
    public function using(Request $request)
    { 
        $OrderSteps = OrderStep::get();
        $contractForm = contractForm::get();
        $orderStepIds = $OrderSteps->pluck('employee_id');
        $employees = Employee::get();
        $Management = Management::get();
        $Department = Department::get();
        $stetues = Status::where('type','order')->get();
        $TypeOrder =  TypeOrder::get();
        $department_workAt = Department::with('workAts')->get();
        return response()->json([
            'success' => true,
            'msg' => '',
            'employees' => $employees,
            'contractForm' => $contractForm,
            'department' => $Department,
            'stetues' => $stetues,
            'typeOrder' => $TypeOrder,
            'department_workAt'=>$department_workAt
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param   $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $max_collection = OrderStep::get()->max('collection');
        $max_collection ++; 
        
        $ids = array_column($request->all(), 'id'); 

        $OrderStepsToDelete = OrderStep::get();
        
        foreach ($OrderStepsToDelete as $OrderStep) {
            $OrderStep->delete();
        }

        $requestData = $request->all();


        foreach ($requestData as $data) {
           
            if($data['employee_id'] != null){    
                $employee = Employee::with(['workAt'=>function($query){
                    $query->where('workable_type','departments');
                }])->where('id',$data['employee_id'])->get();
                
                $emp =$employee->first();
                
            $data['department_id'] = $emp?->workAt?->workable_id ? $emp?->workAt?->workable_id : 0; 
            }
            
            $step = OrderStep::create($data);
            $step->update(['collection'=>$max_collection]);
            
        }

        $OrderStep = OrderStep::get();

        return response()->json([
            'success' => true,
            'message' => '' ,
            'data' =>$OrderStep  
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
        $validated = $request->all(); 

        $order_step =  OrderStep::findOrFail($id);
        $order_step->update($validated);

        return response()->json([
            'success' => true,
            'msg' => 'تم تعديل',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id,$branch_id)
    {
        $order_max = OrderStep::where('branch_id',$branch_id)->max('id');

        $OrderStep = OrderStep::where('id',$id)->first();
        
        if($order_max == $OrderStep->id){
  
            $OrderStep->delete();
            
            return response()->json([
                'success' => true,
                'msg' => 'تم الحذف بنجاح',
            ]);

        } else {

            return response()->json([
                'success' => true,
                'msg' => 'لا يمكن حذف هذا القسم',
            ]);

        }
    }
}
