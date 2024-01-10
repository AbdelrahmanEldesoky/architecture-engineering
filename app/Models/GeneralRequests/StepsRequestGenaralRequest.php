<?php

namespace App\Models\GeneralRequests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StepsRequestGenaralRequest extends Model
{
    use HasFactory;
    protected  $table= "general_request_steps_of_approval";
    protected $guarded = array();

    public function step()
    {
        $this->belongsTo(StepsRequest::class,'steps_of_approval_id');
    }



}
