<?php

namespace App\Rules;

use App\TelgramBot\Database\Admin\LevelAttributeRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Route;

class PercentageValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

     private $percentage;
    public function __construct($percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $total_percent = LevelAttributeRepository::sumOfPercents();
       if(($total_percent + $this->percentage) > 100 )
          return false;
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'total percentage must not greater than 100';
    }
}
