<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionNumberRequest extends FormRequest
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
            'transaction_number'  =>  ['required','string','unique:transaction_number,ref_number'],
            'payment_method'      =>  ['required','exists:list_of_payment_method,id'],
            'amount'              =>  ['required','integer','gt:50'],
        ];
    }

    public function messages()
    {
        return [

            'transaction_number.required'          =>   'transaction number is required',
            'transaction_number.unique'            =>   'transaction number must be unique',
            'payment_method.required'              =>   'payment method is required',
            'payment_method.exists'                =>   'payment method not exist',
            'amount.required'                      =>   'amount is required',
            'amount.integer'                       =>   'amount must be integer',
        ];
    }
}
