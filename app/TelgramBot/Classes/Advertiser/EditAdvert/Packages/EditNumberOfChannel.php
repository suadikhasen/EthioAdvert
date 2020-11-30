<?php

namespace App\TelgramBot\Classes\Advertiser\EditAdvert\Packages;

use Telgrambot\classes\Advertiser\AddPromotionClass\HowManyChannel;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Object\Chat;
use App\EthioAdvertPost;
use App\TelgramBot\Classes\Advertiser\Common\HowManyChannels;

class EditNumberOfChannel extends HowManyChannels
{
    private $number_of_channel;
    private $advert_id;
    private $advert;

    public function __construct($advert_id)
    {
        $this->advert_id = $advert_id;
        $this->advert = AdvertsPostRepository::findAdvert($advert_id);
        $this->number_of_channel = $this->advert->number_of_channel;
        if(!EditAdvertService::validateForEditing($this->advert)){
            exit;
         }
    }

    public function sendEditMessage()
    {  
        $text_message = 'your current number of channel is '.$this->number_of_channel;
        Chat::sendTextMessage($text_message);
        Chat::createQuestion('Edit_Number_Of_Channel',$this->advert_id);
        $this->sendMessage();
        EditAdvertService::putCacheForAdvertId($this->advert_id);
        
    }

    public  function editNumberOfChannel()
    {     
        if($this->maximum_channels <= Chat::$text_message){

            EthioAdvertPost::where('id',$this->advert_id)->update([
                'number_of_channel'  => Chat::$text_message,
                'approve_status'     => false,
            ]);
            EditAdvertService::removeCacheAdvert($this->advert_id);
            EditAdvertService::removeCacheEditAdvertId();
            Chat::deleteTemporaryData();
            Chat::sendTextMessage('number of channels updated successfully');
            
        }else{
            Chat::sendTextMessage('Please Send Number not Greater than the given number');
        }
    }
}
