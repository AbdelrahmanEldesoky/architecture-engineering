<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createStepsApprovaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type'=>'required|integer',
            'employee_id'=>'required|integer',
            'department_id'=>'required|integer',
            'action'=>'required|integer',
            'duration'=>'required|integer',
            'model'=>'required|integer',
        ];
    }
}
