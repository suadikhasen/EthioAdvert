<?php

namespace App\Services\Common;

use App\TelgramBot\Database\Admin\AdvertRepository;
use App\TelgramBot\Database\Admin\ListOfPaymentMethodRepository;
use danog\MadelineProto\API as MadelineProtoAPI;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramBot 
{
   protected static $bot;
   protected static $send_message;

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
     
      try{
         $administrator = $bot->getChatAdministrators([
            'chat_id' => $channel_id,
            ]);
      }catch(TelegramSDKException $exception){
         return false;
      }  
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
            'parse_mode'               => 'HTML',
            'disable_web_page_preview' => true,
            'disable_notification'     => false,

         ]);
      }
      return $message;
   }

   public static function makeAdvertMessage($advert)
   {
       return '<b>   #Advert </b>'."\n".
              '<b> #'.$advert->name_of_the_advert.'</b>'."\n".
              '<b> #'.$advert->description_of_advert.'</b>'."\n"."\n".
               $advert->text_message."\n\n".'@EthioAdvertisementBot';
   }

   public static function sendNotificationForChannelOwner($new,$advert,$channel)
   {
      $text_message = '';
      if($new){
        $text_message = '<b> #notification  </b>'."\n".
                        ' ➡️ <b> Advert '.$advert->name_of_the_advert.'  has been posted .</b>'."\n\n".
                        ' ➡️ <b> on channel '.$channel->name.'.</b>'."\n\n".
                        ' ➡️ <b> this advert will be posted for the next '.($advert->number_of_days-1).' days with out counting today.</b>'."\n\n".
                        ' ➡️ <b> it will live from '.$advert->initial_time.' to '.$advert->final_time.". </b> \n\n".
                        ' ➡️ <b> you will earn '.$advert->channel_price.' ETB from this advert. </b>'."\n"."\n";
      }else{
         $text_message = '#notification  '."\n".
                         'previous advert posted on channel'.$channel->name;
      }
      self::sendNotification($text_message,$channel->channel_owner_id);
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

   public static function sendNotification($message,$username)
    {
        self::initialize()->sendMessage([
            'chat_id'       => $username,
            'text'          => $message,
            'parse_mode'    => 'HTML',
        ]);
    }

    public static function makeDissApproveMessage()
    {
       return '❌ your channel is dis approved ❌'."\n".
              '<b><i>your channel can be dis approve by the following cases</i></b>'."\n".
              '⇒.when you wait more than one day to add bot as admin .'."\n".
              '⇒.when your channel content is pornographic,ethnict conflict,false news .'."\n".
              ' <b>you can apply again after you fix the issue</b> '."\n";
    }

    public  static function getPhotoPath($advert)
    {
     return Cache::remember('photo_url'.$advert->id, now()->addMonth(), function () use ($advert) {
         $bot  = self::initialize();
         $file = $bot->getFile([
            'file_id'  => $advert->image_path
         ]);
       return  'https://api.telegram.org/file/bot1006616206:AAH8kd8j8mZAyzT4zN4in39addGs3hM603E/'.$file->file_path;
          
      });
    }

    public static function sendAdvertApprovementNotification($advert_id)
    {
       $advert  = AdvertRepository::findAdvert($advert_id);
       $payment_method = ListOfPaymentMethodRepository::paymentMethodForAdvertiser();
       $approvication_message = '✅ <b> your advert approved successfully!</b>';
       $payment_method_message = '➡️ <b> Pay '.$advert->amount_of_payment.' ETB using one of the following payment method  listed below. </b>'."\n\n";
       foreach($payment_method as $payment){
         $payment_method_message.= '⇨ <b> Bank Name :'.$payment->bank_name.'</b>'."\n\n".
                                   '⇨ <b> Account Number :'.$payment->account_number.'</b>'."\n\n".
                                   '⇨ <b> Account Holder Name :'.$payment->account_holder_name.'</b>'."\n\n".
                                   '---------------------------';
       }
       self::sendNotification($approvication_message,$advert->advertiser_id);
       self::sendNotification($payment_method_message,$advert->advertiser_id);
    }
}