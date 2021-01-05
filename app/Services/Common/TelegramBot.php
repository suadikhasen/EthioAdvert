<?php

namespace App\Services\Common;

use Telegram\Bot\Api;

class TelegramBot 
{
   protected $bot;

   public static function initialize()
   {
       self::$bot = new Api();
       return self::$bot;
   }
}