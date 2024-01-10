<?php

namespace App\Models\GeneralRequests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralRequestWorkNeed extends Model
{
    use HasFactory;
    public $guarded=array();
    protected $table="general_request_work_needs";
    protected $appends=['typeInArabic','details'];

    public function generalRequest()
    {
        return $this->morphOne(GeneralRequest::CLass, 'requestable');
    }
    public function steps()
    {
        return $this->morphMany(StepsRequest::class, 'requestable');
    }

    public function builder()
    {
        return $this->hasMany(GeneralRequestNeedsDetail::class,'general_request_work_needs_id');
    }
    public function getTypeInArabicAttribute()
    {
        return "احتياجات العمل";
    }

    public function getDetailsAttribute()
    {
        return $this->builder;
    }
}
