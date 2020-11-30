<?php

namespace App\TelgramBot\Classes\Advertiser\AddPromotionClass;

use App\TelgramBot\Common\Services\Advertiser\PromotionService;
use App\TelgramBot\Database\PackageRepositoryService;
use App\TelgramBot\Object\Chat;
use App\Temporary;
use App\EthioAdvertPost;
use Illuminate\Support\Carbon;
class SaveAdvert
{
    private $tempoorary; 
    private $package;
    private $advert_info;
    private $advert;
    private $success_message = '<i> Your Advert Registered Successfully !!</i>'."\n."."\n".
    '<b> Advert Approvication Will be After 30 Minutes '."\n".'if you want to edit the advert info you can edit before the advert is approved</b>'."\n".
    'payment method will be sent after approvication.'.
    '<b><i> Thank You For Choosing Us !!</i></b>';
    
    public function save(): void
    {
        $this->temporary = $this->getTemporaryInfo();
        $this->totalPrice = $this->calculateTotalPrice();
        $chat_id = Chat::$chat_id;
        $this->advert = EthioAdvertPost::create([
            'advertiser_id'           =>   $chat_id,
            'text_message'            =>   $this->temporary['text_message'],
            'image_path'              =>   $this->temporary['image_path'],
            'et_calandar_initial_date'=>   $this->temporary['initial_date'],
            'et_calandar_final_date'  =>   $this->temporary['final_date'],
            'name_of_the_advert'      =>   $this->temporary['name_of_the_advert'],
            'description_of_advert'   =>   $this->temporary['description_of_advert'],
            'amount_of_payment'       =>   $this->totalPrice,
            'active_status'           =>   false,
            'approve_status'          =>   false,
            'payment_status'          =>   false,
            'number_of_channel'       =>   $this->tempoorary['how_many_channels'],
            'package_id'              =>   $this->tempoorary['time_duration_per_day'],
        ]);
        Chat::deleteTemporaryData();
        $this->assignValues();
        $this->sendSuccessfulOrderMessage();
    }


    private function assignValues()
    {

        $this->package  =  PackageRepositoryService::findPackage($this->advert->package_id);
        $this->makeAdvertInfoTextMessage();
    }


    private function sendSuccessfulOrderMessage(): void
    {
        
        Chat::sendTextMessage($this->success_message);
        if($this->advert->image_path  === null){
            Chat::sendTextMessage($this->advert_info);
        }else{
            Chat::sendPhoto(Chat::$chat_id,$this->advert->image_path,$this->advert_info);
        }
    }

    private function getTemporaryInfo(): array
    {
        $temporary = Temporary::where('chat_id', Chat::$chat_id)->where('type', 'Add_Promotion')->get();
        $array = [];
        foreach ($temporary as $single) {
            $array[$single->question] = $single->answer;
        }
        return $array;
    }


    private function makeAdvertInfoTextMessage()
    {
        $this->advert_info =  PromotionService::makeAdvet($this->advert,$this->package);
    }

    private function photoInfo()
    {
        if($this->advert->image_path === null){
            return 'No Photo';
        }else{
            return 'Photo attached on the above';
        }
    }

    private function   calculateTotalPrice()
    {
        $package_price = PackageRepositoryService::getPriceOfThePackge($this->tempoorary['time_duration_per_day']);
        return ($package_price*$this->tempoorary['how_many_days_is_live']);
    }
}