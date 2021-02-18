<?php

namespace App\Http\Controllers\Admin\Levels;

use App\Http\Controllers\Controller;
use App\Services\Common\LevelAssignService;
use App\Services\Common\TelegramBot;
use App\TelgramBot\Database\Admin\ChannelRepository;
use App\TelgramBot\Database\Admin\LevelRepository;
use Illuminate\Support\Str;

class levelAssignationController extends Controller
{
    
    public function assignLevel($channel_id)
    {  
       $channel = ChannelRepository::findChannel($channel_id);
       if($channel->number_of_member >= 50000){
           $level = LevelRepository::findLevel(10);
       }elseif($channel->number_of_member >= 40000){
        $level = LevelRepository::findLevel(11);
       }elseif($channel->number_of_member >= 30000){
        $level = LevelRepository::findLevel(12);
       }elseif($channel->number_of_member >= 20000){
        $level = LevelRepository::findLevel(13);
       }elseif($channel->number_of_member >= 10000){
        $level = LevelRepository::findLevel(14);
       }
       ChannelRepository::UpdatelevelId($channel_id,$level->id);
       $message = '✅ channel <b>'.$channel->name.' is assigned to '.$level->level_name.' !</b>';
       TelegramBot::sendNotification($message,$channel->channel_owner_id);
       return back()->with('success_notification','channel assigned to '.$level->level_name);
    }   
}
