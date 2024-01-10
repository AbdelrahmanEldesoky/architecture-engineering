<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Contract\Contract;
use App\Models\Contract\ContractPayment;
use App\Services\ClientService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $maxId = Contract::max('id');

        $contracts = Contract::withCount(['relatedContracts' => function ($query) {
                        $query->where('date', '>=', Carbon::now()->format('Y-m-d'));
                    }])
                    ->with(['employee' => function($query){
                        $query->select('id', 'first_name' ,'second_name');
                    }])
                    ->whereHas('client',function($query){
                        $query->where('id',auth('api-client')->user()->id);
                    })
                    ->select('id','code','employee_id')
                    ->get()
                    ->map(function ($contracts) {
                            if ($contracts->related_contracts_count > 0) {
                                $contracts->Contract_status = 'جاري العمل';
                            } else {
                                $contracts->Contract_status = 'منتهي';
                            }
                            
                            if($contracts->date > Carbon::now()){
                                $date = Carbon::parse($contracts->date);
                                $contracts->end_date_period = $date->diffInDays(Carbon::now());
                            } else{
                                $contracts->end_date_period = 0;
                            }
                        
                        return $contracts;
                    });

        $payment = Contract::withCount(['relatedContracts' => function ($query) {
                    $query->where('date', '>=', Carbon::now()->format('Y-m-d'));
                }])
                ->whereHas('client', function ($query) {
                    $query->where('id', auth('api-client')->user()->id);
                })
                ->select('id', 'amount')
                ->first();
        
        if ($payment) {
            $payment->amount_payment = $payment->payments ? $payment->payments->sum('amount') : 0;
            $payment->amount_motabaky = $payment->amount - $payment->amount_payment;
            unset($payment->payments);
        } else {
            $payment = (object) [
                'id' => 0,
                'amount' => 0,
                'amount_payment' => 0,
                'amount_motabaky' => 0
            ];
        }

        $contract_work = Contract::where('date' ,'>=', Carbon::now())
            ->where('client_id',auth('api-client')->user()->id)->count();

        $contract_end = Contract::where('date' ,'<', Carbon::now())
            ->where('client_id',auth('api-client')->user()->id)->count();  
      

        return response()->json([
            'success' => true,
            'msg' => '',
            'data'=> $contracts,
            'payment'=>$payment,
            'contract_work' => $contract_work,
            'contract_end' => $contract_end
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function payment()
    {
        
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
        //
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