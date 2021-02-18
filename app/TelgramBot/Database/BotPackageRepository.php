<?php

namespace App\TelgramBot\Database;

use App\EthioAdvertPost;
use App\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BotPackageRepository
{ 
    private static $current;
    private static $before_two_hour;

   public static function findPackagesByNumberOfDays($number_of_days,$per_page,$page_number)
   {
       return Package::with('level')->where('number_of_days',$number_of_days)->paginate($per_page,['*'],'list_of_packages',$page_number);
   }

   public static function findPackage($package_id)
   {
    return Package::with('level')->findOrFail($package_id);
   }

   public static function findPackageByLevelIdForPackageId($package_level_id)
   {
     return Package::where('channel_level_id',$package_level_id)->select('id')->get();
   }

   public static function listOfTakenChannels($collection_of_packages_id,$initial_date)
   {   
       self::$current = Carbon::now();
       self::$before_two_hour =self::$current->copy()->subHours(2);
       return EthioAdvertPost::whereIn('package_id',$collection_of_packages_id)->where('gc_calendar_final_date','>=',$initial_date)->where(function($query){
           $query->where('payment_status',true)->orwhereBetween('created_at',[self::$before_two_hour,self::$current]);
       })->select('assigned_channels')->get();    
   }

   public  static function finAvailableChannels($taken_channel_id)
   {  
       return DB::table('channels')->whereNotIn('channel_id',$taken_channel_id)->pluck('channel_id');
    //    return Channels::whereNotIn('id',$taken_channel_id)->pluck(['id']);
   }

   
   
   
}