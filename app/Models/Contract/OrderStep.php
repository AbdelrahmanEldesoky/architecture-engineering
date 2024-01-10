<?php

namespace App\Models\Contract;

use App\Models\Client;
use App\Models\Country;
use App\Models\Employee\Employee;
use App\Models\Hr\Branch;
use App\Models\Hr\Department;;
use App\Models\MainModelSoft;
use App\Models\State;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\City;
use App\Models\User;
use App\Traits\WorkAtTrait;
use App\Models\WorkAt;
class OrderStep extends MainModelSoft
{
    use HasFactory, WorkAtTrait ;

    protected $table = 'order_steps';
    protected $guarded = [];

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id')->select('id','name');
    }
    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id')->select('id','name');
    }
    public function form() {
        return $this->hasOne(ContractForm::class, 'id', 'form_id')->select('id','name');
    }
    public function employees() {
        return $this->hasOne(Employee::class, 'id', 'employee_id')->select('id','name','first_name','second_name','last_name');
    }
    public function statuses() {
        return $this->hasOne(Status::class, 'id', 'status')->select('id','name');
    }
 

    public function client() {
        return $this->hasOne(Client::class,'id','client_id')->select('id','name');
    }
    public function orderStepsForm() {
        return $this->belongsTo(OrderStepForm::class,'id','order_step_id');
    }
}
