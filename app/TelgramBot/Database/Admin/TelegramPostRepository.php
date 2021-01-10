<?php

namespace App\TelgramBot\Database\Admin;

use App\TelegramPost;
use danog\MadelineProto\API;
use Illuminate\Support\Facades\DB;

class TelegramPostRepository

{
   public static function createTelegramPost($channel_id,$channel_owner_id,$advert_id,$message_id)
   {
    return TelegramPost::create([
       'message_id' => $message_id,
       'channel_id' => $channel_id,
       'ethio_advert_post_id' => $advert_id,
       'active_status'        => 1,
       'channel_owner_id'    => $channel_owner_id,
     ]);
   }
   public static function getChannelsOfAdvert($advert_id)
   {
       return TelegramPost::where('ethio_advert_post_id',$advert_id)->where('active_status',2)->selectRaw('telegram_posts.*')->distinct('channel_owner_id')->get();
   }

   public static function listOfPostsOfAdvert($advert_id)
   {
      return DB::table('telegram_posts')
      ->join('channels','channels.channel_id','=','telegram_posts.channel_id')
      ->where('telegram_posts.ethio_advert_post_id',$advert_id)
      ->selectRaw('channels.channel_id,channels.name,telegram_posts.*')
      ->get()->groupBy('channels.channel_id');
   }
}