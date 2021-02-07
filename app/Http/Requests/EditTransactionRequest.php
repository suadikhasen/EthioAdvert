<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transaction_number'  =>  ['required','string'],
            'payment_method'      =>  ['required','exists:list_of_payment_method,id']
        ];
    }

    public function messages()
    {
        return [
            'transaction_number.required'   =>   'transaction number is required',
            'payment_method.required'       =>   'payment method is required',
            'payment_method.exists'         =>   'payment method not exist'
        ];
    }
}
