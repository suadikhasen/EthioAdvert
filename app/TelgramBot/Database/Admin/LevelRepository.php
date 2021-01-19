<?php

namespace App\TelgramBot\Database\Admin;

use App\ChannelLevel;
use App\Channels;

class LevelRepository
{
    public static function fetchAllLevel()
    {
       return ChannelLevel::simplePaginate(10);
    }

    public static function assignChannelLevelForSatisiserChannels($channel_id,$total_percent_of_channel)
    { 
        $levels = ChannelLevel::where('minimum_percentage_value','<=',$total_percent_of_channel)
        ->where('maximum_percentage_value','>=',$total_percent_of_channel)
        ->first();
        Channels::find($channel_id)->update([
            'level_id' => $levels->id,
          ]);
          return $levels->level_name;
    }

    public static function assignLevelForSuperChannels($channel_id,$total_percent_of_channel)
    {
        $levels = ChannelLevel::orderBy('maximum_percentage_value','dsc')->first();
        channels::find($channel_id)->update([
            'level_id' => $levels->id,
          ]);
          return $levels->level_name;
    }

    public static function checkPecentageUboveness($total_percent_of_channel)
    {
        return ChannelLevel::where('maximum_percentage_value','<=',$total_percent_of_channel)->exists();
    }

    public static function checkPercentageExistence($total_percentage)
    {
       return ChannelLevel::where('minimum_percentage_value','<=',$total_percentage)->where('maximum_percentage_value','>=',$total_percentage)->exists();
    }

    public static function countLevels()
    {
        return ChannelLevel::count();
    }

    public static function findLastAddedLevel()
    {
        $existence = ChannelLevel::exists();
        $return_value = 'no added level';
        if($existence){
            $return_value = ChannelLevel::latest()->first()->level;
        }
        return $return_value;
    }

    public static function createLevel($level_number)
    {
        ChannelLevel::create([
            'level'      => $level_number,
            'level_name' => 'level '.$level_number,
        ]);
    }

    public static function listOfChannelsByLevel($level_id)
    {
        return Channels::where('level_id',$level_id)->simplePaginate(10);
    }

    public static function findLevel($level_id)
    {
        return ChannelLevel::find($level_id);
    }

    public static function allLevel()
    {
        return ChannelLevel::all();
    }
}