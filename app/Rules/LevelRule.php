<?php

namespace App\Rules;

use App\TelgramBot\Database\Admin\LevelRepository;
use Illuminate\Contracts\Validation\Rule;


class LevelRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $compared_value = LevelRepository::findLastAddedLevel();
        if(is_string($compared_value))
          $compared_value = 0;
        return $value > $compared_value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'the level number must be greater than last added level number and successer';
    }

}
