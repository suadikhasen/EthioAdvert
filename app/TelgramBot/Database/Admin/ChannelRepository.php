<?php

namespace App\TelgramBot\Database\Admin;

use Telegram\Bot\Api;
use App\Channels;
use App\TelegramPost;
use Illuminate\Support\Facades\DB;

class ChannelRepository 
{   
    private static $bot;
    private static $chat;
    private static $number_of_members;
    public static function updateChannelInformation($channel_id)
    {   
        self::$bot = new Api();
        self::$chat = self::$bot->getChat(['chat_id' => $channel_id]);
        self::$number_of_members = self::$bot->getChatMembersCount(['chat_id' => $channel_id]);
        Channels::where('channel_id' , $channel_id)->update([
            'username'         => self::$chat->getUsername(),
            'name'             => self::$chat->getTitle(),
            'number_of_member' => self::$number_of_members,
        ]);
    }

    public static function channelsOfUser($user_id)
    {
        return Channels::where('channel_owner_id',$user_id)->simplePaginate(10);
    }

    public static function listOfChannelsByChannelId($level_id)
    {
      return  DB::table('channels')
      ->join('telegram_posts','telegram_posts.channel_id','channels.channel_id')
      ->selectRaw('count(*) as number_of_posts,channels.*')
      ->where('channels.level_id',$level_id)
      ->where('channels.approve_status',true)
      ->where('block_status',false)
      ->where('removed_status','false')
      ->groupBy('channels.channel_id')
      ->orderBy('number_of_posts','asc')
      ->get();
    }

    public static function countOpenedPotsOfChannel($telegram_channel_id,$initial_date,$final_date)
    {
        return TelegramPost::where('channel_id',$telegram_channel_id)->where('active_status','<>',1)->whereBetween('created_at',[$initial_date,$final_date])->select('ethio_advert_post_id')->distinct()->count();
    }
}