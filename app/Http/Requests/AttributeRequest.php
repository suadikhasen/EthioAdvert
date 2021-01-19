<?php

namespace App\Http\Requests;

use App\Rules\PercentageValidation;
use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
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
            'attribute_name'            => 'required|string|unique:level_assignation_attributes,attributes_name',
            'attribute_maximum_value'   => 'required|integer',
            'attribute_percentage_value' => ['required','integer','gt:0','lte:100',new PercentageValidation($this->attribute_percentage_value)]
        ];
    }
}
