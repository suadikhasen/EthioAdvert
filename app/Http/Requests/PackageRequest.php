<?php

namespace App\Http\Requests;

use App\Rules\PackageDuplicate;
use App\Rules\PackagePostingTime;
use Carbon\Carbon;
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
            'package_name'                 => ['required','string','unique:packges,package_name','bail'],
            'package_price'                => ['required','integer','gt:0','bail'],
            'package_number_of_days'       => ['required','integer','gt:0','bail'],
            'package_initial_posting_time' => ['bail','date_format:H:i','required',],
            'package_final_posting_time'   => ['bail','date_format:H:i','required','after:package_initial_posting_time'],
            'package_level'                => ['required','string','exists:chanel_level,level_name','bail']

        ];
    }
}
