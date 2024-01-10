<?php

namespace App\Models\Employee;

use App\Models\Attachment;
use App\Models\MainModelSoft;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class EmployeeInfo extends MainModelSoft
{


    protected $fillable = ['employee_id','personal_photo','id_number','bank_account','bank_iban','bank_name','end_medical_insurance','end_id_number','end_saudi_authority','end_medical_insurance','end_medical_insurance', 'national_id', 'national_photo','border_no','border_photo','passport_no','passport_photo','nationality','gender','birth_date'];

    public function employee() {
        return $this->belongsTo(Employee::class,'id');
    }


}
