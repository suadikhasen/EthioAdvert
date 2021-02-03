<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'email' => ['email','required'],
            'password' => ['required','string']
        ];
    }

    public function messages()
    {
        return[
            'email.email'             => 'the email field must be email ',
            'email.required'          => 'the email field is required',
            'password.required'       => 'the password field is required',
            'password.string'         => 'the password field must be string'
        ];
    }
}
