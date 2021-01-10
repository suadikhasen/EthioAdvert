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