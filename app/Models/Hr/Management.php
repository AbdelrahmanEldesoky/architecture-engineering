<?php

namespace App\Models\Hr;

use App\Models\Contract\Contract;
use App\Models\Country;
use App\Models\Employee\Employee;
use App\Models\MainModelSoft;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkAt;

class Management extends MainModelSoft
{
    use HasFactory;
    protected $table = 'managements';

    protected $fillable = [
        'user_id', 'manager_id','parent_id', 'branch_id', 'manger_name','name', 'type','phone', 'image',
        'attachment', 'status', 'country_id', 'active', 'note'
    ];

    protected $casts = [
        // 'name' => 'array',
        // 'note' => 'array'
    ];

    // public static function tree()
    // {
    //     $allCategories = Management::get();

    //     $rootCategories = $allCategories->whereNull('parent_id');

    //     self::formatTree($rootCategories, $allCategories);

    //     return $rootCategories;
    // }

    // private static function formatTree($categories, $allCategories)
    // {
    //     foreach ($categories as $category) {
    //         $category->childrens = $allCategories->where('parent_id', $category->id)->values();

    //         if ($category->childrens->isNotEmpty()) {
    //             self::formatTree($category->childrens, $allCategories);
    //         }
    //     }
    // }

    public function workAts() {
        return $this->morphMany(WorkAt::Class, 'workable');
    }

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function manger() {
        return $this->belongsTo(User::class, 'manager_id','id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class)->withTrashed();
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function contract() {
        return $this->belongsTo(Contract::class);
    }

    public function departments() {
        return $this->hasMany(Department::class);
    }

    public function directChildren()
    {
        return $this->hasMany(Department::class)->where('type','main');
    }

//    public function employeeManagements() {
//        return $this->hasMany(EmployeeManagement::class);
//    }

    // public function employees() {
    //     return $this->hasMany(Employee::class)->where('draft',0);
    // }
    public function childrens() {
        return $this->hasMany(Management::class, 'parent_id','id');
    }

    public function parent() {
        return $this->belongsTo(Management::class, 'parent_id');
    }

    public function scopeNumberOfEmps() {

        $departments = Department::where('management_id', $this->id)->pluck('id')->toArray();
        $emp_of_all_deps = WorkAt::whereIn('workable_id', $departments)->where('workable_type', 'departments')->count();

        return $emp_of_all_deps + count($this->workers);
    }
    public function scopeGetEmployee() {

        $departments = Department::where('management_id', $this->id)->pluck('id')->toArray();
        $emp_of_all_deps = WorkAt::whereIn('workable_id', $departments)->where('workable_type', 'departments')->pluck('employee_id')->toArray();
        $employee = Employee::whereIn('id',$emp_of_all_deps)->paginate(5);

        return $employee;
    }
}
