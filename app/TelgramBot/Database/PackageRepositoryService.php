<?php

namespace App\TelgramBot\Database;
use App\Package;

class PackageRepositoryService{
    
    /**
     * return unique days of the package
     * 
     */
    public static function retriveuniqueDays()
    {
       return Package::distinct('number_of_days')->orderBy('number_of_days','ASC')->pluck('number_of_days');
    }

    public static function retriveuniqueTime($per_page,$page_number,$number_of_days,$level_id)
    {
        return Package::where('number_of_days',$number_of_days)->where('channel_level_id',$level_id)->paginate($per_page,['*'],'page',$page_number);
    }

    public static function findPackage($id)
    {
        return Package::with('level')->find($id);
    }

    public static function getPriceOfThePackge($id)
    {
        return self::findPackage($id)->price;
    }

    public static function getPackage($number_of_days,$final_postig_time_in_one_day,$channel_level_id,$initial_postig_time_in_one_day)
    {
        return Package::where('number_of_days',$number_of_days)->where('final_postig_time_in_one_day',$final_postig_time_in_one_day)->where('
        channel_level_id',$channel_level_id)->where('initial_postig_time_in_one_day',$initial_postig_time_in_one_day)->first();
    }

}