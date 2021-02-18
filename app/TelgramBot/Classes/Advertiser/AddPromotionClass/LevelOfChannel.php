<?php

namespace App\TelgramBot\Classes\Advertiser\AddPromotionClass;

use App\TelgramBot\Classes\Advertiser\Common\ListOfChannelLevel;
use App\TelgramBot\Common\Services\Advertiser\PromotionService;
use App\TelgramBot\Common\Services\Advertiser\ViewAdvertService;
use App\TelgramBot\Object\Chat;

class LevelOfChannel extends ListOfChannelLevel {

    public function  sendLevelofChannelMessage($page_number=1,$inline=false)
    {  
       $this->sendListOfLevel($page_number,$inline);
       if(!$inline){
          Chat::createQuestion('Add_Promotion','level_of_channel');
       }
    }

    public function selectLevel($response)
    {   
        if(PromotionService::checkInlinekeyboardIsLevelOfChannel()){
            Chat::$text_message = ViewAdvertService::getIDFromViewKeyboard();
            Chat::createAnswer($response->id);
            (new TimeOfTheAdvertPerDay())->sendTimeMessage(1,1,PromotionService::getTemporaryNumberOfDays()->answer);
        }else{
            Chat::sendTextMessage('please use the given keyboard');
        }
        
    }

}