<?php

namespace App\TelgramBot\Classes\Advertiser\EditAdvert\Packages;
use App\Package;
use App\TelgramBot\Classes\Advertiser\Common\ListOfChannelLevel;
use App\TelgramBot\Object\Chat;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Common\Services\Advertiser\PromotionService;
use App\TelgramBot\Common\Services\Advertiser\ViewAdvertService;
use App\TelgramBot\Database\PackageRepositoryService;
use App\EthioAdvertPost;
use App\TelgramBot\Common\GeneralService;

class EditLevelOfChannel extends ListOfChannelLevel
{   
    /**
     * advert id edited advert
     */
    private $advert_id;

    /**
     * previous selected advert levle of channel
     */
    private $advert;

    private $previous_package;
    private $level_of_channel;
    private $updated_package;

    /**
     * constructor of EditLevel Of Channel
     * it assign value for @var $advert_id,@$level_name
     * @param $advert_id
     */


    public function __construct($advert_id)
    {
        $this->advert_id = $advert_id;
        $this->advert = AdvertsPostRepository::findAdvert($this->advert_id);
        if(!EditAdvertService::validateForEditing($this->advert)){
            exit;
         }

    }

    /**
     * send message for list of levels 
     */

    public function sendMessage($page_number=1,$inline=false)
    {  
        
       if(!$inline){
        $this->sendPreviousValue();   
        Chat::createQuestion('Edit_Level_Of_Channel',$this->advert_id);
        EditAdvertService::putCacheForAdvertId($this->advert_id);
       }
       $this->sendListOfLevel($page_number,$inline);
    }

    public function sendPreviousValue()
    {
        Chat::sendTextMessage('Current Level Of The Advert Is '.$this->advert->package->level->level_name);
    }


    public function editLevel()
    {
        if(PromotionService::checkInlinekeyboardIsLevelOfChannel()){
            $this->assignFields();
            $this->update();
            EditAdvertService::removeCacheAdvert($this->advert_id);
            EditAdvertService::removeCacheEditAdvertId();
            GeneralService::answerCallBackQuery('level of channel updated');
        }else{
            Chat::sendTextMessage('please use the given keyboard');
        }
    }

    private function update()
    {
        EthioAdvertPost::where('id',$this->advert_id)->update([
        
            'package_id'         => $this->updated_package->id,
            'approve_status'     => false,
        ]);
    }

    private function assignFields()
    {
        $this->previous_package  =    PackageRepositoryService::findPackage($this->advert->package_id);
        $this->level_of_channel  =    ViewAdvertService::getIDFromViewKeyboard();
        $this->updated_package   =    PackageRepositoryService::getPackage(
            $this->previous_package->number_of_days,
            $this->previous_package->final_postig_time_in_one_day,
            $this->level_of_channel,
            $this->previous_package->initial_postig_time_in_one_day,
        );
    }

   


    
}