<?php
namespace App\TelgramBot\Classes\Advertiser\AddPromotionClass;

use App\TelgramBot\Classes\Advertiser\AddPromotionClass\SaveAdvert;
use App\TelgramBot\Classes\Advertiser\Common\HowManyChannels;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;

class HowManyChannel 
{
   public function sendHowManyChannelMessage($response,$number_of_channels)
   {   
       $this->sendMessage($number_of_channels);
       Chat::createAnswer($response->id);
       Chat::createQuestion('Add_Promotion','how_many_channels');
       Cache::put('number_of_channels'.Chat::$chat_id, $number_of_channels,now()->addMinutes(5));
   }

   public function acceptNumberOfChannel($response)
   {
       if(Cache::get('number_of_channels'.Chat::$chat_id) >= Chat::$text_message && Chat::$text_message != 0){
           (new SaveAdvert())->save($response);
       }else{
           Chat::sendTextMessage('Please Send Number not Greater than the given number');
       }
   }

   public function sendMessage($number_of_channels)
   {
       $text_message = '✅ <b> We have '.$number_of_channels.' free channels. </b>'."\n\n".
         '⬇️ send number of channels you want your advert posted'."\n\n".
         'the number must be less than the number of free channels'."\n";
       Chat::sendTextMessage($text_message);
   }
}