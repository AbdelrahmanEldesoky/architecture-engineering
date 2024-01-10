<?php

namespace App\Models\Employee;

use App\Models\MainModelSoft;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Currency;

class EmployeeFinance extends MainModelSoft
{
    use HasFactory;

    protected $fillable = ["employee_id","currency_id","salary_circle","salary","work_days_in_week","work_hours","allowances","car_allownce","total","hourly_value","minute_value"];



    protected $appends = array('total_hour','salary_day','salary_hour','salary_minute');

    public function getTotalHourAttribute()
    {
        return multiple($this->work_days_in_week,$this->work_hours);
    }

    public function getSalarydayAttribute()
    {
        if($this->work_days_in_week != 0){
        return division($this->salary,$this->work_days_in_week);
        }else{
            return 0;
        }
    }

    public function getSalaryHourAttribute()
    {
        if($this->getTotalHourAttribute() != 0){
        return division($this->salary,$this->getTotalHourAttribute());
        }else{
            return 0;
        }
    }

    public function getSalaryMinuteAttribute()
    {
        return division($this->getSalaryHourAttribute(),60);
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function currency() {
        return $this->hasOne(Currency::class, 'id' , 'currency_id');
    }
}
