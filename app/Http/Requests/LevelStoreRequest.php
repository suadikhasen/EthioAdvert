<?php

namespace App\Http\Requests;

use App\Rules\LevelRule;
use Illuminate\Foundation\Http\FormRequest;

class LevelStoreRequest extends FormRequest
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
            'level_number' => ['required','integer','gt:0',new LevelRule,'unique:chanel_level,level'],
        ];
    }

    public function messages()
    {
        return [
            'level_number.required' => 'the field level number is required',
            'level_number.integer'  => 'the field level must be integer',
            'level_number.gt:0'     => 'the field level must be greater than 0',
            'level_number.unique'   => 'the field level must be unique'
        ];
    }
}
