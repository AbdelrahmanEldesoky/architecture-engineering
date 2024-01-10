<?php

namespace App\Console\Commands;

use App\Models\GeneralRequests\GeneralRequest;
use App\Models\GeneralRequests\StepsRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class terminateSteps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:terminate-steps';

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
        $generalRequests=GeneralRequest::where('status',-1)->with('stepsRequest')->get();
        foreach ($generalRequests as $generalRequest)
        {
            $this->info('i am in general request');

            $last = 0;
            foreach ($generalRequest->stepsRequest as $step)
            {

                $last+=StepsRequest::withTrashed()->find($step->steps_of_approval_id)->duration;
                $this->info('i am in step'.$step->steps_of_approval_id."  ".StepsRequest::withTrashed()->find($step->steps_of_approval_id)->duration." ".$last." ".Carbon::parse($step->created_at )->addHours($last)." ".Carbon::now()->format("Y-m-d H:i:s"));

                if(Carbon::now()>=Carbon::parse($step->created_at )->addHours($last) && $step->status==-1)

                {
                    $step->update(['status'=>1,'note'=>"بواسطة السييتم تلقائيا "]);
                    $generalRequest->update(['in_progress'=>1]);

                }
                if ($step->status == 0 )
                {
                    $generalRequest->update(['status'=>0]);
                    break;

                }
            }
            $lastStatus = $generalRequest->stepsRequest()->orderBy('id','desc')->first()?->status;
            if($lastStatus == 1 || $lastStatus == 2)
                $generalRequest->update(['status'=>1]);


        }
    }
}
