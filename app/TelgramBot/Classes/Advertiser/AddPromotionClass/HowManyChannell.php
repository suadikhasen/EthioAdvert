<?php
namespace Telgrambot\classes\Advertiser\AddPromotionClass;

use App\TelgramBot\Classes\Advertiser\AddPromotionClass\SaveAdvert;
use App\TelgramBot\Classes\Advertiser\Common\HowManyChannels;
use App\TelgramBot\Object\Chat;

class HowManyChannel extends HowManyChannels
{


   public function sendHowManyChannelMessage()

   {   $this->sendMessage();
       Chat::createQuestion('Add_Promotion','how_many_channels');
   }

   public function acceptNumberOfChannel($response)
   {
       if($this->maximum_channels<=Chat::$text_message){
           Chat::createAnswer($response->id);
           (new SaveAdvert())->save();
       }else{
           Chat::sendTextMessage('Please Send Number not Greater than the given number');
       }
   }
}