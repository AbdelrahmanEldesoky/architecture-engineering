<?php

namespace App\Models;


use App\Models\Contract\Contract;
use App\Models\Contract\OrderClient;
use App\Models\Contract\OrderStep;
use App\Models\Contract\OrderStepForm;
use App\Models\Contract\ContractForm;
use App\Models\Contract\TypeOrder;
use App\Models\Employee\Employee;
use App\Models\Hr\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Laravolt\Avatar\Facade as Avatar;
use Nwidart\Modules\Facades\Module;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Client extends AuthModel
{
    use LogsActivity;

    protected $guard = "client";
    protected $table = 'clients';

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'phone_verified_at','from',
        'branch_id', 'broker_id', 'status_id','image',
        'card_id', 'passport_id', 'card_image', 'passport_image', 'gender', 'birth_date', 'client_request_id',
        'active', 'note', 'type', 'register_number', 'register_image', 'agent_name', 'letter_head', 'confirmed'
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function request()
    {
        return $this->belongsTo(ClientRequest::class);
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function order()
    {
        return $this->belongsTo(OrderClient::class,'id','client_id');
    }

    public function OrderStepForm()
    {
        return $this->hasMany(OrderStepForm::class,'client_id','id')->withTrashed();
    }
    public function orderType()
    {
        return $this->belongsTo(TypeOrder::class,'order_type','id');
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    public function profile()
    {
        return $this->belongsTo(User::class, 'profile_id')->withTrashed();
    }

    public function projects()
    {
        return (Module::has('Projects')) ? $this->hasMany(\Modules\Projects\Entities\Project::class) : null;
    }

    public function contracts()
    {
        return  $this->belongsTo(Contract::class,'id','client_id') ;
    }
    
    public function forms_constract()
    {
        return  $this->hasMany(ContractForm::class) ;
    }
    
    public function getPath(Media $media)
    {
        return storage_path('public/clients/' . $media->id . '/');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "This JobType has been {$eventName}")
            ->logOnly(['name', 'email', 'phone', 'password', 'phone_verified_at',
                'branch_id', 'broker_id', 'status_id',
                'card_id', 'passport_id', 'card_image', 'passport_image', 'gender', 'birth_date',
                'active', 'note', 'type', 'register_number', 'register_image', 'agent_name', 'letter_head', 'confirmed'
            ])
            ->logOnlyDirty()
            ->useLogName('system');;
        // Chain fluent methods for configuration options
    }

    public function getAvatarAttribute()
    {
        return Avatar::create(Str::title($this->name))->toBase64();
    }

}
