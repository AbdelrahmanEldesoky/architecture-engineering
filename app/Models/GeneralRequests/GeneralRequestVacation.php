<?php

namespace App\Models\GeneralRequests;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralRequestVacation extends Model
{
    use HasFactory;
    public $guarded=array();
protected $table="general_request_vacation";
    protected $appends=['typeInArabic','vacation_credit'];

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
        return "اجازة";
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getVacationCreditAttribute()
    {
        return $this?->employee?->vacation?->vacation_credit;
    }

}
