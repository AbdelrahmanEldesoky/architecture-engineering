<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTrack extends Model
{
    use HasFactory;

    protected $guarded = array();


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
