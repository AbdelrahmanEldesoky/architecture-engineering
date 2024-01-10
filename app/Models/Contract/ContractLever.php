<?php

namespace App\Models\Contract;

use App\Models\Country;
use App\Models\Employee\Employee;
use App\Models\MainModelSoft;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\City;
use App\Models\User;
use App\Traits\WorkAtTrait;
use App\Models\WorkAt;
class ContractLever extends MainModelSoft
{
    use HasFactory, WorkAtTrait ;

    protected $table = 'contract_levers';
    protected $guarded = [];
    protected $appends = ['card_path'];

    public function getCardPathAttribute()
    {
        return asset('/storage/'.$this->card_image);
    }

    public function contracts() {
        return $this->hasMany(Contract::class,'id','contract_id');
    }
    public function type() {
        return $this->belongsTo(ContractPayment::class,'type','id');
    }

}
