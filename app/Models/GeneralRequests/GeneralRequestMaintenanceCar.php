<?php

namespace App\Models\GeneralRequests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralRequestMaintenanceCar extends Model
{
    use HasFactory;

    public $guarded=array();
    protected $appends=['typeInArabic','details'];

    public function generalRequest()
    {
        return $this->morphOne(GeneralRequest::class, 'requestable');
    }

    public function steps()
    {
        return $this->morphMany(StepsRequest::class, 'requestable');
    }

    public function builder()
    {
        return $this->hasMany(GeneralRequestMaintenanceCarDetail::class);
    }
    public function getTypeInArabicAttribute()
    {
        return "صيانه سيارة ";
    }

    public function getDetailsAttribute()
    {
        return $this->builder;
    }
}
