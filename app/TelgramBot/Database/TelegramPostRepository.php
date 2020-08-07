<?php


namespace App\TelgramBot\Database;


use App\TelegramPost;

class TelegramPostRepository
{
   public static function findListOfAdverts($chat_id,$per_page,$page_number)
   {
       return TelegramPost::with(['channelsName','adverts'])->where('channel_owner_id',$chat_id)->paginate($per_page,['*'],'page',$page_number)->toJson();
   }


}
