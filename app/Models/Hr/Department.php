<?php

namespace App\Models\Hr;

use App\Models\CMS\Service;
use App\Models\MainModelSoft;
use App\Models\User;
use App\Models\WorkAt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends MainModelSoft
{
    use HasFactory;

    protected $fillable = ['name','type','management_id','manager_id','parent_id','note','active'];


    public function workAts() {
        return $this->morphMany(WorkAt::Class, 'workable');
    }
    public function management() {
        return $this->belongsTo(Management::class);
    }

    public function childrens() {
        return $this->hasMany(Department::class, 'parent_id','id');
    }
    public function manger() {
        return $this->belongsTo(User::class, 'manager_id','id');
    }
    public function scopeNumberOfEmps() {

        $departments = Department::where('management_id', $this->id)->pluck('id')->toArray();
        $emp_of_all_deps = WorkAt::whereIn('workable_id', $departments)->where('workable_type', 'departments')->count();

        return $emp_of_all_deps + count($this->workers);
    }

    public function departmentServices()
    {
        return $this->hasMany(DepartmantServices::class);
    }



}
