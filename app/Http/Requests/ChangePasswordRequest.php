<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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

            'old_password'    =>  ['required'],
            'current_password' => ['required','confirmed'],
            'current_password_confirmation' => ['required'],           
        ];
    }

    public function messages()
    {
        return[
            'old_password.required'      =>  'old password is required',
            'current_password.required'  =>  'old password is required',
            'current_password.confirmed' =>  'current password not macthed'
        ];
    }
}
