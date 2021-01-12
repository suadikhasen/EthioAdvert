<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class PackagePostingTime implements Rule
{
    private Carbon $final_time;
    private Carbon $initial_time;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Carbon $initial_time,Carbon $final_time)
    {
        $this->initial_time = $initial_time;
        $this->final_time  = $final_time;
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
        $compared_value = $this->initial_time->diffInHours($this->final_time,false);
        if($compared_value >= 2)
          return true;
          return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Time Difference  must be greater than or equal to 2 hour';
    }
}
