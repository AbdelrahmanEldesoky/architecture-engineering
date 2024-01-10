<?php

namespace App\Models\Contract;

use App\Models\Country;
use App\Models\Employee\Employee;
use App\Models\MainModelSoft;
use App\Models\State;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\City;
use App\Models\User;
use App\Traits\WorkAtTrait;
use App\Models\Hr\Management;
use App\Models\WorkAt;
class OrderClient extends MainModelSoft
{
    use HasFactory, WorkAtTrait ;

    protected $table = 'order_clients';
    protected $guarded = [];
    public function management()
    {
        return $this->belongsTo(Management::class,'management_id','id');
    }

}
