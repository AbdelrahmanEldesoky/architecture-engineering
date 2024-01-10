<?php

namespace App\Models\GeneralRequests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GeneralRequestMaintenanceCarDetail extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    public $guarded=array();
    protected $table="general_request_maintenance_car_details";
    protected $appends=['pictures'];

    public function parent()
    {
        return $this->belongsTo(GeneralRequestMaintenanceCar::class);
    }

    public function getPicturesAttribute()
    {
        return $this->getMedia();
    }
}
