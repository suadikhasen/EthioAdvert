<?php

namespace App\TelgramBot\Classes\Advertiser\EditAdvert\Packages;

use App\TelgramBot\Classes\Advertiser\Common\HowManyDays;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use App\TelgramBot\Database\PackageRepositoryService;
use App\EthioAdvertPost;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;

class  EditNumberOfDays extends HowManyDays
{

    private $previous_package;
    private $advert_id;
    private $advert;


    public function __construct($advert_id)
    {
        $this->advert_id         =    $advert_id;
        $this->advert            =    AdvertsPostRepository::findAdvert($this->advert_id);
        $this->previous_package  =    PackageRepositoryService::findPackage($this->advert->package_id);
        if(!EditAdvertService::validateForEditing($this->advert)){
            exit;
         }
    }


    public function sendEditMessage()
    {
        Chat::sendTextMessage('the current value is ' . $this->previous_package->number_of_days);
        $this->sendMessage();
        Chat::createQuestion('Edit_Number_Of_Days', $this->advert_id);
        EditAdvertService::putCacheForAdvertId($this->advert_id);
    }

    public function edit()
    {
        if ($this->validateDay()) {
            $this->finishEditing();
        } else {
            Chat::sendTextMessage('please send correct number liste on the above');
        }
    }

    private function finishEditing()
    {
        $this->assignFields();
        $this->update();
        Chat::deleteTemporaryData();
        EditAdvertService::removeCacheAdvert($this->advert_id);
        EditAdvertService::removeCacheEditAdvertId();
        Chat::sendTextMessage('number of days updated sccesdsfully');
    }

    private function assignFields()
    {
        $this->updated_package = PackageRepositoryService::getPackage(
            Chat::$text_message,
            $this->previous_package->final_postig_time_in_one_day,
            $this->previous_package->channel_level_id,
            $this->previous_package->initial_postig_time_in_one_day,
        );
    }

    public function update()
    {
        EthioAdvertPost::where('id', $this->advert_id)->update([
            'package_id'         => $this->updated_package->id,
            'approve_status'     => false,
        ]);
    }
}
