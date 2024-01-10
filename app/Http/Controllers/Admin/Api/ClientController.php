<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\MainController;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Broker;
use App\Models\Client;
use App\Models\Hr\Branch;
use App\Models\Status;
use App\Services\ClientService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Models\Contract\OrderStepForm;
use App\Models\Contract\OrderClient;
use App\Models\Contract\OrderStep;
use Validator;
class ClientController extends MainController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        // parent::__construct();
        // $this->class = "client";
        // $this->table = "clients";
        // $this->middleware('auth');
        // $this->middleware('permission:clients.view', ['only' => ['index', 'show']]);
        // $this->middleware('permission:clients.create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:clients.edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:clients.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $service = new ClientService();
        $clients = $service->search($request->search)
            ->with('broker', 'status', 'branch', 'contracts')
            ->withCount(['contracts' => function ($q) {
                $q->where('end_date', '>', Carbon::now()->format('Y-m-d'));
            }])
            ->get()
            ->map(function ($client) {
                if ($client->contracts === null) {
                    $client->Contract_status = 'لا يوجد عقود';
                } else {
                    if ($client->contracts_count > 0) {
                        $client->Contract_status = 'جاري العمل';
                    } else {
                        $client->Contract_status = 'منتهي';
                    }
                }
                return $client;
            });



        return response()->json([
            'success' => true,
            'msg' => '',
            'data' => $clients,
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
        return response()->json([
            'success' => true,
            'msg' => '',
            'statuses'=> $statuses,
            'branches'=>$branches,
            'brokers'=>$brokers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        try {
                DB::beginTransaction();
               $validated = $request->all(); 

               $max_collection = OrderStep::get()->max('collection');
               $validated['collection'] = $max_collection; 

                $validated['from'] = 'dashboard';
                if($request->name != null){
                    $validated['name'] = $request->name;
                }

                $client =  Client::create($validated);
                if (!empty($validated['card_image']) && $validated['card_image'] != null) {
                    
                    $validated['card_image'] = uploadFile($request->card_image, "clients",$client->id,'card_image');
                    $client->update(['card_image'=>$validated['card_image']]);
                }elseif(!empty($validated['register_image']) && $validated['register_image'] != null){
                    $validated['register_image'] = uploadFile($request->register_image, "clients",$client->id,'register_image');
                    $client->update(['register_image'=>$validated['register_image']]);
                }
                $user =auth()->user() ;
                activity()
                    ->performedOn($client)
                    ->causedBy($user)
//                    ->withProperties(['card_image' =>  $validated['card_image']])
                    ->withProperties(['card_image' => isset($validated['card_image']) ? $validated['card_image'] : null])
                    ->event('updated')
                    ->useLog($user->name)
                    ->log('Client Has been Updated');
                    
                    DB::commit();
                return response()->json([
                    'success' => true,
                    'msg' => 'تم تسجيل عميل جديد',
                    ],200);
        }catch (QueryException $e) {
                    DB::rollBack();
                    return response()->json([
                        'success'=>false,
                        'msg' => 'An error'
                    ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */

    public function show(Request $request, $id)
    {
        // $title = __("names.client-data");
        // $class = $this->class;
        // $client = Client::findOrFail($id);

        // $data_all = Contract::where('client_id',$id)->latest();
        // $data = $data_all->paginate($this->limit);
        // $statuses = DB::table('contracts')->leftJoin('statuses','statuses.id','=','contracts.status_id')->select(DB::raw('count(*) as total, statuses.name as status, statuses.color as color', 'statuses.id as id'))->groupBy('status_id')->get();

        // $payments = ContractPayment::whereIn('contract_id',$data_all->pluck('id'))->sum('amount');
        // $logs = $client->activities;
        // $tree = array_merge($this->tree, [route('admin.clients.index') => 'clients']);
        
        // response()->json([
        //     'status' => true,
        //     'msg' => 'تم تسجيل عميل جديد',
        //     'data'=>
        // ]);
        
        // return view('admin.clients.show', compact('data','client', 'class','title' ,'statuses','payments','logs','tree'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(Request $request)
    {

        if ($request->all() === []) {
            return response()->json([
                'success' => true,
                'msg' => '',
                'data' => null 
            ]);    
        }
        $client = Client::with('broker','status','branch')
                    ->when( !empty($request->name)  ,function ($query)use($request){
                        $query->where('name', 'like','%'.$request->name.'%');
                    })
                    ->when( !empty($request->name)  ,function ($query)use($request){
                        $query->where('name', 'like','%'.$request->name.'%');
                    })
                    ->when( !empty($request->phone)  ,function ($query)use($request){
                            $query->where('phone','like','%'.$request->phone.'%');
                    })
                    ->when( !empty($request->branch_id)  ,function ($query)use($request){
                        $query->where('branch_id',$request->branch_id);
                    })
                    ->when( !empty($request->broker_id)  ,function ($query)use($request){
                        $query->where('broker_id',$request->broker_id);
                    })
                    ->when( !empty($request->type)  ,function ($query)use($request){
                        $query->where('type',$request->type);
                    })->first();

        return response()->json([
            'success' => true,
            'msg' => '',
            'data' => $client 
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, $id)
    {
        $validated = $request->all(); 
        
        $client = Client::findOrFail($id);

        if ($validated['type'] == 'company') {
            $validated['name'] = $validated['name'];
            $validated['card_id'] = null;
            $validated['card_image'] = null;
            if (!empty($validated['register_image'])) {
                $validated['register_image'] = uploadFile($request->register_image, "clients",$client->id,'register_image');
            } else {
                $validated = Arr::except($validated, array('register_image'));
            }
        } else {
            $validated['name'] = null;
            $validated['register_number'] = null;
            $validated['agent_name'] = null;
            $validated['register_image'] = null;
            if (!empty($validated['card_image'])) {
                $validated['card_image'] = uploadFile($request->card_image, "clients",$client->id,'card_image',true);
            } else {
                $validated = Arr::except($validated, array('card_image'));
            }
        }
        
        $client->update($validated);
    
        return response()->json([
            'success' => true,
            'msg' => 'تم التعديل بنجاح',
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
            $clients = Client::withCount('projects')->get();
        }else{
            $clients = Client::withCount('projects')->whereIn('id', $request->id)->get();
        }
        
        foreach ($clients as $client) {
            if ($client->projects_count == 0) {
                $client->delete();
            } 
        }

        return response()->json([
            'success' => true,
            'message' => 'تم الحذف بنجاح'   
        ]);
    }

    public function requests(){
        $tree = array_merge($this->tree, [route('admin.clients.index') => 'clients']);
        return view('admin.clients.requests',compact('tree'));
    }




    public function showRequest($requestId){
        $tree = array_merge($this->tree, [route('admin.clients.requests', $requestId) => 'clients']);
//        $requestId = $requestId;
        return view('admin.clients.request_view',compact('requestId','tree'));
    }
}
