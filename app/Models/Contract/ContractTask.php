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
class ContractTask extends MainModelSoft
{
    use HasFactory, WorkAtTrait ;

    protected $table = 'contract_tasks';
    protected $guarded = [];
    public function contracts() {
        return $this->hasMany(Contract::class,'id','contract_id');
    }

    public function employees() {
        return $this->hasOne(Employee::class,'id','employee_id');
    }

}
