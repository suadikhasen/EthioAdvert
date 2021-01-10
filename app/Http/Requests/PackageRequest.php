<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            'package_name' => ['required','string','unique:packges,package_name'],
            'package_price' => ['required','integer','gt:0'],
            'package_number_of_days' => ['required','integer','gt:0'],
            'package_initial_posting_time' => ['required'],
            'package_final_posting_time'   => ['required'],
            'package_level'                => ['required','string','exists:packges,package_name']
        ];
    }
}
