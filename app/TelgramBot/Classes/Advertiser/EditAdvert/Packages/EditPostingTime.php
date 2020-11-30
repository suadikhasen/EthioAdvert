<?php

namespace App\TelgramBot\Classes\Advertiser\EditAdvert\Packages;

use App\TelgramBot\Classes\Advertiser\Common\PostingTime;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Common\Services\Advertiser\PromotionService;
use App\TelgramBot\Common\Services\Advertiser\ViewAdvertService;
use App\EthioAdvertPost;

class EditPostingTime extends PostingTime
{
    
    private $advert_id;
    private $advert;

    public function __construct($advert_id)
    {
        $this->advert_id = $advert_id;
        $this->advert = AdvertsPostRepository::findAdvert($advert_id);
        if(!EditAdvertService::validateForEditing($this->advert)){
            exit;
         }
    }

    public function sendEditMessage($per_page,$page_number,$number_of_days,$level_id,$inline)
    {  
        $this->sendPreviousValue();
        $this->sendMessage($per_page,$page_number,$number_of_days,$level_id,$inline);
        if(!$inline){
            Chat::createQuestion('Edit_Posting_Time',$this->advert_id);
            EditAdvertService::putCacheForAdvertId($this->advert_id);
        }
    }

    private function sendPreviousValue()
    {
        $text_message = 'your current postig time in day is from '.$this->advert->package->initial_postig_time_in_one_day.
        'to '.$this->advert->package->final_postig_time_in_one_day;
        Chat::sendTextMessage($text_message);
    }

    public function edit()
    {
        if(PromotionService::checkInlineKeyboardIsTimeSelectionForPromotion()){
            $this->update();
            Chat::deleteTemporaryData();
            EditAdvertService::removeCacheAdvert($this->advert_id);
            EditAdvertService::removeCacheEditAdvertId();
            Chat::sendEditTextMessage('posting time update successfully ',null,Chat::$chat_id,GeneralService::getInlineMessageID());
            GeneralService::answerCallBackQuery('editing done successfully');
        }else{
            Chat::sendTextMessage('please use the givenkeyboard');
        }
    }

    private function update()
    {
        EthioAdvertPost::where('id',$this->advert_id)->update([
            'package_id'  => ViewAdvertService::getIDFromViewKeyboard(),
            'approve_status'     => false,
        ]);
    }
}