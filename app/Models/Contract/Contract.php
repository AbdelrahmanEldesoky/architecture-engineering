<?php

namespace App\Models\Contract;

use App\Models\Client;
use App\Models\Country;
use App\Models\Employee\Employee;
use App\Models\Hr\Branch;
use App\Models\Hr\Management;
use App\Models\MainModelSoft;
use App\Models\State;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\City;
use App\Models\User;
use App\Traits\WorkAtTrait;
use App\Models\WorkAt;
class Contract extends MainModelSoft
{
    use HasFactory, WorkAtTrait ;

    protected $table = 'contracts';
    protected $guarded = [];
    // protected $appends = ['card_path'];

    // public function getCardPathAttribute()
    // {
    //     return asset($this->card_image);
    // }

    

    public function relatedContracts()
    {
        // Define the relationship or method logic here
        // For example:
        return $this->hasMany(Contract::class,'id','id');
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }
    
    public function tasks() {
        return $this->hasMany(ContractTask::class,'contract_id','id');
    }

    public function payments() {
        return $this->hasMany(ContractPayment::class,'contract_id','id');
    }
    public function levers() {
        return $this->hasMany(ContractLever::class,'contract_id','id');
    }

    public function client() {
        return $this->hasOne(Client::class,'id','client_id');
    }
    public function employee() {
        return $this->hasOne(Employee::class,'id','employee_id');
    }

    public function branch() {
        return $this->hasOne(Branch::class,'id','branch_id');
    }
    public function management()
    {
        return $this->hasOne(Management::class, 'id', 'management_id');
    }
    public function type () {
        return $this->hasone(ContractType::class, 'id', 'contract_type_id');
    }
    public function scopeTransferedGetContractData() {
        return $this::where('client_id','<>', null)->select('id','number','code','contract_type_id','status_id','client_id','branch_id','management_id','date')
        ->with([
            'client'=>function($query) {
                $query->select('id','name_first','name_last','name','phone');
            },
           'branch' => function($query) {
                $query->select('id','name');
           },
           'management' => function ($query) {
                $query->select('id','name','manger_name');
           },
           'items' => function ($query) {
            $query->select('id','period');
           },
           'status' => function ($query) {
            $query->select('id','name','color');
           },
           'owner' => function ($query) {
            $query->select('id','name');
           }
        ]);
    }


    public function scopeGetNewContractData() {
        return $this::where('client_id', null)->select('id','number','code','contract_form_id','status_id','branch_id','management_id','date')
        ->with([
           'branch' => function($query) {
                $query->select('id','name');
           },
           'management' => function ($query) {
                $query->select('id','name','manger_name');
           },
           'items' => function ($query) {
            $query->select('id','period');
           },
           'status' => function ($query) {
            $query->select('id','name','color');
           },
           'owner' => function ($query) {
            $query->select('id','name');
           }
        ]);
    }

    public function scopeGetTotalData() {
        return $this::select('id','number','code','contract_form_id','status_id','branch_id','management_id','client_id','date','client_id')
        ->with([
           'client' => function($query) {
                $query->select('id','name');
           },
           'branch' => function($query) {
                $query->select('id','name');
           },
           'management' => function ($query) {
                $query->select('id','name','manger_name');
           },
           'items' => function ($query) {
            $query->select('id','period');
           },
           'status' => function ($query) {
            $query->select('id','name','color');
           },
           'owner' => function ($query) {
            $query->select('id','name');
           }
        ]);
    }


}
