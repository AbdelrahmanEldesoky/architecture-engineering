<?php

namespace App\Models\GeneralRequests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralRequestNeedsDetail extends Model
{
    use HasFactory;
    public $guarded=array();
    protected $table="general_request_work_needs_details";

    public function generalRequest()
    {
        return $this->morphOne(GeneralRequest::CLass, 'requestable');
    }
    public function steps()
    {
        return $this->morphMany(StepsRequest::class, 'requestable');
    }
    public function parent()
    {
        return $this->belongsTo(GeneralRequestWorkNeed::class);
    }
}
