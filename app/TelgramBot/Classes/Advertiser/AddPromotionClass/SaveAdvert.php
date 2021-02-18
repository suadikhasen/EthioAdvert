<?php

namespace App\TelgramBot\Classes\Advertiser\AddPromotionClass;

use App\TelgramBot\Common\Services\Advertiser\PromotionService;
use App\TelgramBot\Database\PackageRepositoryService;
use App\TelgramBot\Object\Chat;
use App\Temporary;
use App\EthioAdvertPost;
use Illuminate\Support\Facades\Cache;

class SaveAdvert
{
    private $temporary; 
    private $package;
    private $advert_info;
    private $advert;
    private $success_message = '<i>✅ Your Advert Ordered Successfully !!</i>'."\n.\n".
    '<b>➡️ Advert  Will be Approved After few Minutes. </b>'."\n".
    '<b>➡️ payment method will be sent after approvication. </b>'."\n\n\n".
    '<b><i> Thank You For Choosing Us !!</i></b>';
    private $assigned_channels;
    public function save($response): void
    {
        $this->temporary = $this->getTemporaryInfo();
        $this->totalPrice = $this->calculateTotalPrice();
        $chat_id = Chat::$chat_id;
        $this->assigned_channels = $this->assigned_channels();
        $this->advert = EthioAdvertPost::create([
            'advertiser_id'           =>   $chat_id,
            'text_message'            =>   $this->temporary['text_message'],
            'image_path'              =>   $this->temporary['image_path'],
            'gc_calendar_initial_date'=>   $this->temporary['initial_date'],
            'gc_calendar_final_date'  =>   Cache::get('final_date'.Chat::$chat_id),
            'name_of_the_advert'      =>   $this->temporary['name_of_the_advert'],
            'description_of_advert'   =>   $this->temporary['description_of_advert'],
            'amount_of_payment'       =>   $this->totalPrice,
            'active_status'           =>   1,
            'approve_status'          =>   0,
            'payment_status'          =>   0,
            'number_of_channel'       =>   Chat::$text_message,
            'package_id'              =>   $this->temporary['select_package'],
            'channel_price'           =>   (0.95*$this->package->price),
            'package_name'            =>   $this->package->package_name,
            'number_of_days'          =>   $this->package->number_of_days,  
            'one_package_price'       =>   $this->package->price,
            'initial_time'            =>   $this->package->initial_posting_time_in_one_day,
            'final_time'              =>   $this->package->final_postig_time_in_one_day,
            'assigned_channels'       =>   $this->assigned_channels,
        ]);
        Chat::createAnswer($response->id);
        Chat::deleteTemporaryData();
        $this->makeAdvertInfoTextMessage();
        $this->sendSuccessfulOrderMessage();
    }

    private function assigned_channels()
    {
        $available_channels = Cache::get('available_channels'.Chat::$chat_id);
        $collection = collect($available_channels);
        return $collection->take(Chat::$text_message)->toArray();
    }

    private function sendSuccessfulOrderMessage(): void
    {
        
        Chat::sendTextMessage($this->success_message);
        if($this->advert->image_path  == null){
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
            if($single->question == 'image_path' && $single->answer == 'no'){
                $array[$single->question] = null;
            }else{
                $array[$single->question] = $single->answer;
            }
        }
        return $array;
    }


    private function makeAdvertInfoTextMessage()
    {
        $this->advert_info =  PromotionService::makeAdvet($this->advert);
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
        $this->package = PackageRepositoryService::findPackage($this->temporary['select_package']);
        $package_price = $this->package->price;
        return ($package_price*Chat::$text_message);
    }
}