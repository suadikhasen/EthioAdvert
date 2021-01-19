<?php

namespace App\Services\Common;

use danog\MadelineProto\API as MadelineProtoAPI;
use danog\MadelineProto\MTProto;
use Telegram\Bot\Api;

class TelegramBot 
{
   protected $bot;
   protected $send_message;

   public static function initialize()
   {
       self::$bot = new Api();
       return self::$bot;
   }

   public static function mtProto()
   {  
       $token = env('TELEGRAM_BOT_TOKEN');
       $madeLineProto = new MadelineProtoAPI('session.madeline');
      return $madeLineProto->botLogin($token);
       
   }

   public static function checkChannelAuthorization($channel_id)
   {
     $bot= TelegramBot::initialize();
     $status = true; 
     $administrator = $bot->getChatAdministrators([
        'chat_id' => $channel_id,
        ]);
      foreach($administrator as $admin){
         if($admin->user->id === $bot->getMe()->id && $admin->user->isBot && $admin->canPostMessages && $admin->canEditMessages && $admin->canDeleteMessages && $admin->canSendMessages && $admin->canSendMediaMessages ){
            $status = true;
         }
      }
     return $status;
   }

   public static function sendAdvert($channel_id,$advert)
   {
      $authorized_bot = TelegramBot::initialize();
      $text_message = self::makeAdvertMessage($advert);
      $message = '';
      if($advert->image_path === null){
         $message = $authorized_bot->sendMessage([
            'chat_id'                  => $channel_id,
            'text'                     => $text_message,
            'parse_mode'               => 'HTML',
            'disable_web_page_preview' => true,
            'disable_notification'     => false,
         ]);
      }else{
         $message = $authorized_bot->sendPhoto([
            'caption' => $text_message,
            'chat_id' => $channel_id,
            'photo'   => $advert->image_path,
         ]);
      }
      return $message;
   }

   public static function makeAdvertMessage($advert)
   {
       return '<b> #Advert </b>'."\n".
              '<b> #'.$advert->name_of_the_advert."\n".
              '<b> #'.$advert->description_of_the_advert."\n"."\n".
              '<i>'.$advert->text_message.'</i>';
   }

   public static function sendNotificationForChannelOwner($new,$advert,$earning,$channel)
   {
      $text_message = '';
      if($new){
        $text_message = '#notification  '."\n".
                        $advert->name_of_the_advert.'  has been posted on channel '.$channel->name."\n".
                        'this advert will be posted for the next'.($advert->number_of_days-1).'days with out counting today'."\n".
                        'it will live from'.$advert->initial_time.' to '.$advert->final_time.
                        'you will earn '.$earning.' from this advert'."\n"."\n".
                        '----- <b> Thank You For Working With Us </b>---------';    
      }else{
         $text_message = '#notification  '."\n".
                         'previous advert posted';
      }
      self::initialize()->sendMessage(['chat_id' => $channel->channel_id,'text'=>$text_message,'parse_mode' => 'HTML']);
   }

   public static function sendNotificationForAdvertiser($advert,$channels,$new)
   {
     $text_message = self::makeAdvertiserNotificationMessage($new,$channels,$advert);
     self::initialize()->sendMessage([
        'chat_id' => $advert->advertiser_id,
        'text'     => $text_message,
        'parse_mode' => 'HTML', 
     ]);
   }

   public static function makeAdvertiserNotificationMessage(bool $new,array $channels,$advert)
   {
      $text_message='';
      $list_of_channels  = '';
      foreach($channels as $channel){
        $list_of_channels.= '<b> @'.$channel->username.'</b>'."\n";
      }
      if($new){
        $text_message = '<b>#notification</b>'."\n".
                        '<b>your advert '.$advert->name_of_the_advert.'is posted</b>'."\n".
                        'the advert is posted for the next '.($advert->number_of_days-1).' days with out counting today'."\n".
                        'the advert will live from '.$advert->initial_time.' to '.$advert->final_time."\n".
                        'the advert is posted on those channels'."\n".
                         $list_of_channels."\n"."\n".'---------<b> Thank You For Working With Us </b>---------';
      }else{
         '<b>#notification</b>'."\n".
         '<b>your previous opened  advert '.$advert->name_of_the_advert.'is posted on previous channels sent for you</b>'."\n".
         "\n"."\n".'---------<b> Thank You For Working With Us </b>---------';
      }
      return $text_message;
   }
}