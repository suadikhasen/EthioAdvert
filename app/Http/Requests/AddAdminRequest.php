<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddAdminRequest extends FormRequest
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
            'full_name'              =>   ['required','string'],
            'email'                  =>   ['required','email','unique:admins,email'],
            'password'               =>   ['required','confirmed','string'],
            'password_confirmation'  =>   ['required','string']
        ];
    }

    public function messages()
    {
        return [
            'full_name.required'    => 'full name is required',
            'full_name.required'    => 'full name must be string',
            'email.required'        => 'email is required',
            'email.email'           => 'email field must be email type',
            'email.unique'          => 'email must be unique',
            'password.required'     => 'password field is required',
            'password.confirmed'    => 'password not confirmed',
            'password.string'       => 'password must be string',
            'password_confirmation' => 'password confirmation field is required',
        ];
    }
}
