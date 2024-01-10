<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ContractRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required',
            'date' => 'required|date',
            'card_image' => 'nullable|mimes:jpeg,png,jpg,gif,pdf',
            'details'=>'nullable',
            'contract_type_id'=>'required',
            'client_id' =>'required|exists:clients,id',
            'branch_id' => 'required|exists:branches,id',
            'management_id'=> 'required|exists:managements,id',
            'employee_id' => 'required|exists:employees,id',
            'period' =>'required|integer',
            //'type' =>'required',
            'amount'=>'required|integer',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'msg'   => 'Validation errors',
            'data'      => $validator->errors()
        ],400));

    }
    public function messages()
    {
        return [

        ];
    }
}
