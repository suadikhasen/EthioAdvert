<?php

namespace App\Services\Common;

use App\LevelOfChaannel;
use App\TelgramBot\Database\Admin\LevelAttributeRepository;
use App\TelgramBot\Database\Admin\LevelRepository;
use Carbon\Carbon;

class LevelAssignService
{  
    private static $chat_id;
    private static $channel_id;
    private static $quality_of_channel;
    private static $number_of_subscriber;
    private static $list_of_weekly_posts;
    private static $average_perday_post;
    private static $weekly_average_number_of_view;

    private static $percent_of_quality;
    private static $percent_of_average_per_day_post;
    private static $percent_of_weekly_average_number_of_view;
    private static $percent_of_number_of_subscriber;

    private static $total_percent_of_channel;

    private static $return_status;

    public static function checkPostExstenceInGivenWeek($channel_id)
    {
        
    }
    

    public static function assignLevel($chat_id,$channel_id,$quality_of_channel)
    {  
        self::$chat_id                   =    $chat_id;
        self::$channel_id                =    $channel_id;
        self::$quality_of_channel        =    $quality_of_channel;

       self::$number_of_subscriber                       =  self::getNumberOfSubscriber();
       self::$list_of_weekly_posts                       =  self::listOfWeeklyPosts();
         
       if(array_count_values(self::$list_of_weekly_posts->messages) < 0){
          return 'no post';
       }

       self::$average_perday_post                        =  self::averagePerDayPosts();
       self::$weekly_average_number_of_view              =  self::calculateWeeklyAverageNumberOfView();

       self::$percent_of_average_per_day_post            =  self::calculatePerDayPostPercentage();
       self::$percent_of_number_of_subscriber            =  self::calculateNumberOfSubscriberPercentage();
       self::$percent_of_quality                         =  self::calculateQualityPercentage();
       self::$percent_of_weekly_average_number_of_view   =  self::calculateNumberOfWeeklyAveragePostViewPercentage();
       self::$total_percent_of_channel                   =  self::sumOfPercents();
       
       return self::selectLevel();
    }

    private static function selectLevel()
    {
       if(LevelRepository::checkPercentageExistence(self::$total_percent_of_channel)){
           return LevelRepository::assignChannelLevelForSatisiserChannels(self::$channel_id,self::$total_percent_of_channel);
       }elseif(LevelRepository::checkPecentageUboveness(self::$total_percent_of_channel)){
          return LevelRepository::assignLevelForSuperChannels(self::$channel_id,self::$total_percent_of_channel);
       }else
         return 'not assigned';
    }

    private static function sumOfPercents()
    {
        return  (
            self::$percent_of_weekly_average_number_of_view
            +self::$percent_of_average_per_day_post+
            self::$percent_of_number_of_subscriber+
            self::$percent_of_quality);    
    }

    

    private static function getNumberOfSubscriber()
    {
      $bot = TelegramBot::initialize();
      return $bot->getChatMembersCount([
          'chat_id' => self::$channel_id
      ]);
    }

    private static function listOfWeeklyPosts()
    {
      $mtproto = TelegramBot::mtProto();
      return  $mtproto->messages->getHistory([
          'peer'      => self::$chat_id,
          'min_date'  => strtotime(Carbon::today()),
          'max_date'  => strtotime(Carbon::today()->addDays(8)),
          'limit'     => 100
      ]);
    }

    private static function averagePerDayPosts()
    {
        return count(self::$list_of_weekly_posts->messages)/7;
    }

    private static function calculateWeeklyAverageNumberOfView()
    {
         $sum = 0;
         foreach(self::$list_of_weekly_posts->messages as $message)
         {
           $sum = $sum+$message->views;
         }
         return ($sum/7);
    }

    private static function calculateQualityPercentage()
    {
       $quality_attribute = LevelAttributeRepository::findByName('quality');
       $percent = $quality_attribute->attribute_percentage_value;
       $maximum_value = $quality_attribute->attribute_maximum_value;
       return self::calculatePercentage($percent,$maximum_value,self::$quality_of_channel);

    }

    private static function calculatePerDayPostPercentage()
    {
       $quality_attribute = LevelAttributeRepository::findByName('average perday post');
       $percent = $quality_attribute->attribute_percentage_value;
       $maximum_value = $quality_attribute->attribute_maximum_value;
       return self::calculatePercentage($percent,$maximum_value,self::$quality_of_channel);

    }

    private static function calculateNumberOfSubscriberPercentage()
    {
       $quality_attribute = LevelAttributeRepository::findByName('number of members ');
       $percent = $quality_attribute->attribute_percentage_value;
       $maximum_value = $quality_attribute->attribute_maximum_value;
       return self::calculatePercentage($percent,$maximum_value,self::$quality_of_channel);

    }

    private static function calculateNumberOfWeeklyAveragePostViewPercentage()
    {
       $quality_attribute = LevelAttributeRepository::findByName('weekly average post view ');
       $percent = $quality_attribute->attribute_percentage_value;
       $maximum_value = $quality_attribute->attribute_maximum_value;
       return self::calculatePercentage($percent,$maximum_value,self::$quality_of_channel);

    }

    private static function calculatePercentage($percent,$maximum_value,$given_value)
    {
      return ($given_value/$maximum_value)*$percent;
    }

}