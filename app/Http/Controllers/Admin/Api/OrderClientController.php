<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\MainController;
use App\Http\Requests\OrderClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\Status;
use App\Models\Contract\OrderClient;
use App\Models\Contract\TypeOrder;
use App\Models\Contract\OrderStepForm;
use App\Models\Employee\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\OrderClientStepFormRequest;
use App\Models\Contract\OrderStep;
use App\Models\GeneralRequests\GeneralRequest;
use App\Models\Hr\Branch;
use App\Models\Hr\Department;
use Validator;
class OrderClientController extends MainController
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


        $data = Employee::where('id', $this->getEmployeeForUser()->id)
        ->with(['workAt' => function ($query) {
            $query->with('workable');
        }])
        ->get();
        
        $this->department = $data->first()->workAt->workable_id;

        foreach ($data as $employee) {
            if ($employee->workAt instanceof Branch) {
                $this->branchId = $employee->workAt->workable->id;
            } else if ($employee->workAt instanceof Management) {
                $this->branchId = $employee->workAt->workable->branch->id;
            } else {
                $this->branchId = $employee?->workAt?->workable?->management?->branch?->id;
            }

        }
    }
    public function index(Request $request)
    {
        $response = [];

        $clients = Client::with(['orderType','branch'=>function($query){
            $query->select('id','name');
        },'order','OrderStepForm' => function ($query) {
            $query->with(['orderStep'=>function ($q) {
                $q->withTrashed();
            }
            ,'orderStep.department'])
                ->whereHas('orderStep', function ($subquery) {
<<<<<<< HEAD
                    $subquery->where('employee_id', $this->getEmployeeForUser()->id);
                });
        }])
=======
                $subquery->where(function ($q) {
                    $q->where('employee_id', $this->getEmployeeForUser()->id)
                        ->orWhere('employee_id','=',0)->where('department_id',$this->department);
                })
                ->where(function ($q) {
                        $q->whereNotNull('deleted_at')
                            ->orWhereNull('deleted_at');
                    });
                }); 
            }])
        ->when( !empty($request->search)  ,function ($query)use($request){
                $query->where('name','like','%'. $request->search .'%');
                $query->orWhere('phone','like','%'. $request->search .'%');
                $query->orWhere('card_id','like','%'. $request->search .'%');    
        })
>>>>>>> b8cfb9c93605fd57849089a420d4408b294a6556
        ->when( !empty($request->dateFrom)  ,function ($query)use($request){
            $query->whereDate('created_at', $request->dateFrom);
         })
         ->when( !empty($request->typeOrder)  ,function ($query)use($request){
<<<<<<< HEAD
            $query->whereDate('created_at', $request->typeOrder);
=======
            $query->where('order_type', $request->typeOrder);       
>>>>>>> b8cfb9c93605fd57849089a420d4408b294a6556
         })
         ->when( !empty($request->dateTo)  ,function ($query)use($request){
            $query->whereHas("OrderStepForm", fn($q) => $q->whereDate('created_at', $request->dateTo));
         })
         ->when( !empty($request->branch_id)  ,function ($query)use($request){
            $query->where('branch_id', $request->branch_id);       
         })
         ->when( !empty($request->typeClient)  ,function ($query)use($request){
            $query->where('type', $request->typeClient);       
         })
         ->when( !empty($request->status)  ,function ($query)use($request){
            $query->whereHas("order", fn($q) => $q->where('status', $request->status));       
         })
        ->withCount([
            'order as order_count'])
        ->select('id','name',\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created_date'),'type',
<<<<<<< HEAD
        'branch_id','order_type')
        ->orderBy('id', $request->orderDesc ? $request->orderDesc :'desc' )
=======
        'branch_id','order_type','collection')
        ->orderBy('id', $request->sortBy ? 'desc' : 'asc')
>>>>>>> b8cfb9c93605fd57849089a420d4408b294a6556
        ->get()
        ->map(function ($clients) {
           $order_step_from = OrderStepForm::where('collection',$clients->collection)
                        ->where('employee_id',$this->getEmployeeForUser()->id)->first();
            $clients->order_count = $order_step_from?->status;
            $clients->step_id = $clients?->order?->step_id;
            $clients->branch_name = $clients?->branch?->name;
            $clients->note = $clients?->order?->note;
            $clients->form_id = $clients?->order?->form_id;
<<<<<<< HEAD
            $clients->order_type_name = $clients->orderType->name;
            if ($clients->order_count == 33) {
                $clients->step_status = 'معتمد';
                $clients->step_status_id = 33;
            } else{
=======
            $clients->order_type_name = $clients?->orderType?->name;        
            if ($clients->order_count == 33) {
                $clients->step_status = 'معتمد';
                $clients->step_status_id = 33;    
            } elseif ($clients?->order?->last_status_id ==100 ){
                $clients->step_status = 'مقبول';
                $clients->step_status_id = 100;  
            } elseif ($clients?->order?->last_status_id == 99 ){
                $clients->step_status = 'مرفوض';
                $clients->step_status_id = 99;  
            } else {
>>>>>>> b8cfb9c93605fd57849089a420d4408b294a6556
                if ($clients->order_count > 0) {
                    $clients->step_status = 'تحت الاجراء';
                    $clients->step_status_id = 2;
                } else {
                    $clients->step_status = 'اتخاذ اجراء';
                    $clients->step_status_id = 1;
                }
            }

           unset($clients->branch,$clients->order_type,$clients->orderType,$clients->order);
            return $clients;
        });

        foreach ($clients as $client) {
            if (!$client->OrderStepForm->isEmpty()) {
                // unset($client->OrderStepForm);
                $response[] = $client;
            }
        }

        return response()->json([
            'success' => true,
            'msg' => '',
            'data' =>$response,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   $request
     * @return \Illuminate\Http\Response
     */
    public function addStep(OrderClientStepFormRequest $request)
    {
        $validated = $request->all();

      $next_step = OrderStep::where('id','>=',$request->order_step_id)
                                ->where('collection',$request->collection)
                                ->where(function ($q) {
                                    $q->whereNotNull('deleted_at')
                                        ->orWhereNull('deleted_at');
                                })->withTrashed()->skip(1)->first();
                              
       $max_step = OrderStep::where('collection',$request->collection)
                            ->where(function ($q) {
                                $q->whereNotNull('deleted_at')
                                    ->orWhereNull('deleted_at');
                            })->withTrashed()->get()->max('id');

      $order_client = OrderClient::where('step_id',$request->order_step_id)
                             ->where('collection',$request->collection)
                              ->where('client_id',$request->client_id)->first();
<<<<<<< HEAD

        $OrderStepForm = OrderStepForm::where('order_step_id',$request->order_step_id)
                              ->where('client_id',$request->client_id)->first();

=======
 
     $OrderStepForm = OrderStepForm::where('order_step_id',$request->order_step_id)
                              ->where('collection',$request->collection)
                              ->where('client_id',$request->client_id)->first();           
                              
>>>>>>> b8cfb9c93605fd57849089a420d4408b294a6556
        $OrderStepForm->update([
            'status'=>$request->status,
            'note'=>$request->note,
            'form_id'=>$request->form_id,
            'employee_id' => $this->getEmployeeForUser()->id
        ]);

        if($request->status == 33 || $request->order_step_id == $max_step){
            $order_client->update([
                'status'=>$request->status,
                'note'=>$request->note,
                'last_status_id'=>$request->status
            ]);

            $OrderStepForm->update([
                'last_status_id'=>$request->status
            ]);
        } else {
            $OrderStepForm =  OrderStepForm::create([
                'status'=> 0,
                'order_step_id' => $next_step->id,
                'form_id' => $next_step->form_id,
                'client_id'=> $request->client_id,
                'collection'=>$request->collection,
            ]);

            $order_client->update([
                'status'=> $request->status,
                'step_id'=> $next_step->id,
                'form_id' => $next_step->form_id,
                'note'=>$request->note,
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'تم بنجاح',
        ],200);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */

     public function statusOrder(Request $request)
     {
         $clients = Client::where('id',$request->client_id)
                     ->with(['OrderStepForm' =>function($query){
                          $query->select('id',\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as end_date'),'client_id','note','order_step_id','status');
 
                         },'OrderStepForm.statuses','OrderStepForm.orderStep'=>function($query){
                             $query->withTrashed()->select('id','department_id','employee_id');
                     },'OrderStepForm.orderStep.employees','OrderStepForm.orderStep.department'])
                     ->select('id','name',\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created_date'),'type',
                     'branch_id','order_type')
                     ->get();
 
         return response()->json([
                         'success' => true,
                         'msg' => 'تم بنجاح',
                         'data' => $clients
                     ],200);
 
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function showOrder(Request $request)
    {
        $response = [];

        $clients = Client::where('id', $request->client_id)
        ->with(['orderType','branch'=>function($query){
            $query->select('id','name');
        },'order','OrderStepForm' => function ($query) {
            $query->with(['orderStep','orderStep.department'])
                ->whereHas('orderStep', function ($subquery) {
                    $subquery->where('employee_id', $this->getEmployeeForUser()->id);
                });
        }])
        ->withCount(['order'=>function($query){
            $query->select('status');
        }])
        ->select('id','name',\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created_date'),'type',
        'branch_id','order_type')
        ->get()
        ->map(function ($clients) {
          //  $clients->management_name = $clients->order->management->name;
            $clients->step_id = $clients->order->step_id;
            $clients->branch_name = $clients->branch->name;
            $clients->note = $clients->order->note;
            $clients->form_id = $clients->order->form_id;
            $clients->order_type_name = $clients->orderType->name;

           unset($clients->branch,$clients->order_type,$clients->orderType,$clients->order);
            return $clients;
        });

        foreach ($clients as $client) {
            if (!$client->OrderStepForm->isEmpty()) {
               // unset($client->OrderStepForm);
                $response[] = $client;
            }
        }

        return response()->json([
            'success' => true,
            'msg' => '',
            'data' =>$response,
        ],200);

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


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

    }

}
