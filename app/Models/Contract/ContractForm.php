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
class ContractForm extends MainModelSoft
{
    use HasFactory, WorkAtTrait ;

    protected $table = 'contract_forms';
    protected $guarded = [];

}
