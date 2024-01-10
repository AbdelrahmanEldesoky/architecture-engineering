<?php

namespace App\Models\Contract;

use App\Models\Client;
use App\Models\Country;
use App\Models\Employee\Employee;
use App\Models\MainModelSoft;
use App\Models\State;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\City;
use App\Models\User;
use App\Traits\WorkAtTrait;
use App\Models\WorkAt;
class OrderStepForm extends MainModelSoft
{
    use HasFactory, WorkAtTrait ;

    protected $table = 'order_step_forms';
    protected $guarded = [];

    public function form() {
        return $this->hasOne(ContractForm::class, 'id', 'form_id')->select('id','name');
    }
    public function statuses() {
        return $this->hasOne(Status::class, 'id', 'status')->select('id','name');
    }

    public function orderStep() {
        return $this->hasMany(OrderStep::class, 'id', 'order_step_id')
                    ->withTrashed();
    }
    public function client()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }

}
