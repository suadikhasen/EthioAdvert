<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodForAdvertiserRequest extends FormRequest
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
            'payment_method_name'                                       => ['required','string','unique:list_of_payment_method,bank_name'],
            'payment_method_holder_identification_number'               => ['required','numeric'],
            'payment_method_holder_name'                                => ['required','string'],
        ];
    }
}
