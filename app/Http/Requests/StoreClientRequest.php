<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreClientRequest extends FormRequest
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
            'type' => 'required|in:individual,company',
            'name' => 'required|string',
            'card_id' => 'required_if:type,individual|numeric|unique:clients,card_id',
            'card_image' => 'nullable|mimes:jpeg,png,jpg,gif,pdf',
           // 'company_name' => 'nullable|required_if:type,company|string',
            'register_number' => 'nullable|required_if:type,company|numeric',
            'agent_name' => 'nullable|required_if:type,company|string',
            'register_image' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'required|numeric|unique:clients,phone',
            'email' => 'nullable|email',
            'branch_id' => 'required|exists:branches,id',
            'broker_id' => 'nullable|exists:brokers,id',
            'letter_head' => 'nullable|string'
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
