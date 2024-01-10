<?php

namespace App\Models\GeneralRequests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GeneralRequestCustody extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    public $guarded=array();
    protected $appends=['typeInArabic','pictures'];

    protected $table = "general_request_custody";
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
        return "العهدة";
    }
    public function getPicturesAttribute()
    {
        return $this->getMedia();
    }

}
