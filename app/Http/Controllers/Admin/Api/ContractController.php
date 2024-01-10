<?php

namespace App\Http\Controllers\Admin\Api;

use App\Helpers\RequestHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Requests\ContractRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Broker;
use App\Models\Client;
use App\Models\Contract\Contract;
use App\Models\Contract\ContractTask;
use App\Models\Contract\ContractType;
use App\Models\Hr\Branch;
use App\Models\Hr\Management;
use App\Models\Status;
use App\Services\ClientService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Projects\Entities\ContractPayment;
use App\Models\Employee\Employee;
use Validator;
class ContractController extends MainController
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

    public function index(Request $request)
    {
        $contract = Contract::with(['client','employee', 'tasks','tasks.employees', 'payments', 'management', 'type', 'branch', 'levers','levers.type'])
            ->when( !empty($request->client_id)  ,function ($query)use($request){
                $query->whereHas("client", fn($q) => $q->where('id', $request->client_id));       
            })
            ->when( !empty($request->client_phone)  ,function ($query)use($request){
                $query->whereHas("client", fn($q) => $q->where('phone','like','%'. $request->client_phone .'%'));       
            })
            ->when( !empty($request->employee_name)  ,function ($query)use($request){
                $query->whereHas("employee", function($q)use($request) {
                    $q->where('name','like','%'. $request->employee_name .'%');
                    $q->orWhere('first_name','like','%'. $request->employee_name .'%');
                    $q->orWhere('second_name','like','%'. $request->employee_name .'%');
                    $q->orWhere('last_name','like','%'. $request->employee_name .'%');
                });       
            })
            ->when( !empty($request->code)  ,function ($query)use($request){
                $query->where('code', $request->code);     
            })

            ->when( !empty($request->branch_id)  ,function ($query)use($request){
                $query->where('branch_id', $request->branch_id);     
            })
            ->when( !empty($request->duration)  ,function ($query)use($request){
                $query->where('duration', $request->duration);     
            })   
            ->get()
            ->map(function ($contract) {
                $date = Carbon::parse($contract->date);
                $contract->dateEnd = $date->addDays($contract->period)->format('Y-m-d');

                if($contract->date > Carbon::now()){
                    $date = Carbon::parse($contract->date);
                    $contract->end_date_period = $date->diffInDays(Carbon::now());
                } else{
                    $contract->end_date_period = 0;
                }
                foreach($contract->payments as $payment){
                    if($payment->status === null ||$payment->status === 0){
                        $payment->status_name = ' ';
                    }else{
                        $contractTask = ContractTask::where('id',$payment->status)->first();
                        if($contractTask != null){
                            $payment->status_name = 'بعد الانتهاء من '.$contractTask->name;
                        }else{
                            $status = abs($payment->status);
                            $payment->status_name = 'بعد '.$status .' يوم';
                        } 
                    }
                }
                return $contract;
            });
    
            

            
        $contract_work = Contract::where('end_date' ,'>', Carbon::now())->count();

        $contract_end = Contract::where('end_date' ,'<=', Carbon::now())->count(); 

        return response()->json([
            'success' => true,
            'msg' => '',
            'data' => $contract,
            'contract_work' => $contract_work,
            'contract_stop'=> 0,
            'contract_payment'=> 0,
            'contract_end'=> $contract_end,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function using(Request $request)
    {
        $statuses = Status::where('type','client')->get(['id','name']);    
        $branches = Branch::get();
        $brokers = Broker::where('name', 'like', '%' . $request->brokerName . '%')->get();
        $management = Management::get();
        $contractType = ContractType::get();
        $client = Client::get();
        $employees = Employee::get();
        return response()->json([
            'success' => true,
            'msg' => '',
            'statuses'=> $statuses,
            'branches'=>$branches,
            'brokers'=>$brokers,
            'management'=>$management,
            'contractType'=>$contractType,
            'client'=>$client,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContractRequest $request)
    {
        $validated = $request->all(); 
        
        $date = Carbon::parse($request->date);
        $date->addDays($request->period);
        $end_date = $date->format('Y-m-d');
        
        $validated['end_date'] = $end_date;

        
        $contract =  Contract::create($validated);

        if (!empty($validated['card_image'])) {
            $validated['card_image'] = uploadFile($request->card_image, "contract",$contract->id,'card_image');
            $contract->update(['card_image'=>$validated['card_image']]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'تم تسجيل عقد جديد',
            'data'=>$contract,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */

    public function show($id)
    {
       
        $contract = Contract::with(['client','employee', 'tasks','tasks.employees', 'payments', 'management', 'type', 'branch', 'levers'])->where('id',$id)->get()
            ->map(function ($contract) {
                $date = Carbon::parse($contract->date);
                $contract->dateEnd = $date->addDays($contract->period)->format('Y-m-d');

                if($contract->date > Carbon::now()){
                    $date = Carbon::parse($contract->date);
                    $contract->end_date_period = $date->diffInDays(Carbon::now());
                } else{
                    $contract->end_date_period = 0;
                }

                return $contract;
            });

        $contract = $contract->first();

        if($contract){
            return response()->json([
                'success' => true,
                'msg' => '',
                'data' => $contract,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'msg' => 'لا يوجد بيانات',
                'data' => null,
            ],404);
        }

        
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
        $contract =  Contract::findOrFail($id);

        if (!empty($validated['card_image'])) {
            $validated['card_image'] = uploadFile($request->card_image, "contract",$contract->id,'card_image',true);
        }

        $contract->update($validated);


        return response()->json([
            'success' => true,
            'msg' => 'تم تعديل ',
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->type == 'all'){
            $contracts = Contract::get();
        }else{
            $contracts = Contract::whereIn('id', $request->id)->get();
        }
        
        foreach ($contracts as $contract) {
                $contract->delete(); 
        }

        return response()->json([
            'success' => true,
            'message' => 'تم الحذف بنجاح'   
        ]);
    }
}
