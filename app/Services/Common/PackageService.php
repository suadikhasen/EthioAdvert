<?php

namespace App\Services\Common;

use Carbon\Carbon;
use  App\TelgramBot\Database\Admin\PackageRepository;

class PackageService
{
    public static function validPackageTime($initial_time,$final_time)
    {   
        $initial_time = Carbon::parse($initial_time);
        $final_time   = Carbon::parse($final_time);
        $compared_value = $initial_time->diffInHours($final_time,false);
        if($compared_value >= 2)
          return true;
          return false;
    }

    public static function checkPackageUniqueness($level_name, $initial_time,$final_time,$number_of_days)
    {
        $existence = PackageRepository::checkPackageDuplication($level_name,$initial_time,$final_time,$number_of_days);
        if($existence)
           return false;
        return true;
    }
}