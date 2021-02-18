<?php

namespace App\TelgramBot\Database\Admin;

use App\ChannelLevel;
use App\Package;
use Carbon\Carbon;
use Illuminate\Support\Str;

class  PackageRepository

{   
    public static function allPackages()
    {
        return  Package::with('level')->simplePaginate(10);
    }

    public static function checkPackageDuplication($level_name,$initital_posting_time,$final_posting_time,$number_of_days)

    {
        $level_id = Str::after($level_name,' ');
        return Package::where('channel_level_id',$level_id)
        ->where('initial_posting_time_in_one_day',$initital_posting_time)
        ->where('final_postig_time_in_one_day',$final_posting_time)
        ->where('number_of_days',$number_of_days)->exists();
    }

    public static function createPackage($request)
    {  
        $level = ChannelLevel::where('level_name',$request->package_level)->first();

        Package::create([
           'package_name' => $request->package_name,
           'channel_level_id' => $level->id,
           'initial_posting_time_in_one_day' => Carbon::parse($request->package_initial_posting_time),
           'final_postig_time_in_one_day' => Carbon::parse($request->package_final_posting_time),
           'price' => $request->package_price,
           'number_of_days' => $request->package_number_of_days,
        ]);
    }
}