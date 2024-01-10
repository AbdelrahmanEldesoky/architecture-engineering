<?php

namespace App\Models\GeneralRequests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralRequestAdvance extends Model
{
    use HasFactory;
    public $guarded=array();
    protected $appends=['typeInArabic'];

    protected $table="general_request_advance";

    public function generalRequest()
    {
        return $this->morphOne(GeneralRequest::CLass, 'requestable');
    }

    public function steps()
    {
        return $this->morphMany(StepsRequest::class, 'requestable');
    }

    public function getTypeInArabicAttribute()
    {
        return "سلفة";
    }

}
