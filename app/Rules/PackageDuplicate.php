<?php

namespace App\Rules;

use App\TelgramBot\Database\Admin\PackageRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class PackageDuplicate implements Rule
{

    private $level_name;
    private $initital_posting_time;
    private $final_posting_time;
    private $number_of_days;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($level_name,$initital_posting_time,$final_posting_time,$number_of_days)
    {
        $this->level_name = $level_name;
        $this->initital_posting_time = Carbon::parse($initital_posting_time);
        $this->final_posting_time = Carbon::parse($final_posting_time);
        $this->number_of_days = $number_of_days;
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
        $existence = PackageRepository::checkPackageDuplication($this->level_name,$this->initital_posting_time,$this->final_posting_time,$this->number_of_days);
        if($existence)
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
        return 'Package Already Exist Please Change.';
    }
}
