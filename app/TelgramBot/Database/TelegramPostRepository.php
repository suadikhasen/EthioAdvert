<?php


namespace App\TelgramBot\Database;

use App\TelegramPost;

class TelegramPostRepository
{
   public static function findListOfAdverts($chat_id,$per_page,$page_number)
   {
       return TelegramPost::with(['ChannelsName','adverts'])->where('channel_owner_id',$chat_id)
       ->groupBy('ethio_advert_post_id','channel_id')->paginate($per_page,['*'],'page',$page_number);
   }

   public static function checkExistenceOfPost($chat_id)
   {
    return TelegramPost::where('channel_owner_id',$chat_id)->exists();
   }
   
   
}
