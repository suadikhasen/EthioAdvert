<?php

namespace App\Http\Requests;

use App\TelgramBot\Database\Admin\LevelAttributeRepository;
use Illuminate\Foundation\Http\FormRequest;

class AddQuality extends FormRequest
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
        $quality = LevelAttributeRepository::findByName('quality');
        $maximum = $quality->attribute_maximum_value;
        return [
            'quality' => ['required','gte:0','lte:'.$maximum],
        ];
    }
}
