<?php

namespace App\Console\Commands;

use App\Models\Contract\OrderStep;
use App\Models\Contract\OrderClient;
use App\Models\Contract\OrderStepForm;
use Carbon\Carbon;
use Illuminate\Console\Command;

class terminateClientSteps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tetstjjj';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clientStepForms = OrderStepForm::where('status', 0)->with(['orderStep','client'])->get();

        $arr = [];
        $last_period =0;
        //$arr[$index] =
        foreach ($clientStepForms as $index => $clientStepForm) {

            foreach ($clientStepForm->orderStep as $step) {

                $last_period = $step->period;
                $branchId = $clientStepForm->client->branch_id;
                $client_id = $clientStepForm->client_id;
                $max_step_id = OrderStep::where('branch_id',$branchId)->get()->max('id');
                $next_step = OrderStep::where('branch_id',$branchId)
                            ->where('id','>=',$clientStepForm->order_step_id)->skip(1)->first();

                if((Carbon::now()>=Carbon::parse($clientStepForm->created_at )->addHours($last_period)) && ($max_step_id != $branchId)){
                    $OrderClient = OrderClient::where('client_id',$client_id)->first();

                    $clientStepForm->update([
                        'status'=> -1,
                        'note'=>'بواسطة السييتم تلقائيا'
                    ]);

                    $OrderClient->update([
                        'status'=>-1,
                        'note'=>'بواسطة السييتم تلقائيا',
                        'step_id'=>$next_step->id,
                    ]);

                    $OrderStepForm =  OrderStepForm::create([
                        'status'=> 0,
                        'order_step_id' => $next_step->id,
                        'form_id' => $next_step->form_id,
                        'client_id'=> $client_id,
                    ]);

                    $OrderClient->update([
                        'status'=> -1,
                        'step_id'=> $next_step->id,
                        'form_id' => $next_step->form_id,
                        'note'=>'بواسطة السييتم تلقائيا',
                    ]);
                }

            }
        }

    }
}
