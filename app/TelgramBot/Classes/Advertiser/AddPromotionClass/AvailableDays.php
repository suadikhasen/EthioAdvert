<?php

namespace App\TelgramBot\Classes\Advertiser\AddPromotionClass;

use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\BotPackageRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Collection;

class AvailableDays
{   
    private $package;
    private $package_level_id;
    private $number_of_days;
    private $initial_date;
    private $final_date;
    private $coming_package_id;
    private $page_number;
    private $added_day;
    private $count=1;

    public function handle($package_id,$page_number,$per_page,$added_day,$initial_date)
    {  
       $this->count++;
       $this->added_day          =   $added_day; 
       $this->page_number        =   $page_number;
       $this->coming_package_id  =   $package_id;
       $this->package            =   BotPackageRepository::findPackage($package_id);
       $this->package_level_id   =   $this->package->channel_level_id;
       $this->number_of_days     =   $this->package->number_of_days;
       $this->initial_date       =   $initial_date;
       $this->final_date         =   $this->initial_date->addDays($this->number_of_days-1);
       $this->findAvailableDays();
    }

    private function findAvailableDays()
    {
        $collection_of_packages_id     =   BotPackageRepository::findPackageByLevelIdForPackageId($this->package_level_id);
        $collection_of_packages_id     =   $collection_of_packages_id->toArray(); 
        $taken_channels                =   BotPackageRepository::listOfTakenChannels($collection_of_packages_id,$this->initial_date);
        $splited_channel_id            =   $this->splitChannelId($taken_channels);
        $available_channels            =   $this->firstOptionAvailableChannels($splited_channel_id);
        if($available_channels->count() >= 1){
            Cache::put('first_option_available_channels'.Chat::$chat_id, $available_channels->pluck('id'),now()->addMinutes(5));
            $this->sendMessage();
        }else{
           $second_option_channel = $this->findSecondOptionFreeChannels($available_channels); 
           if($second_option_channel->isNotEmpty()){
              Cache::put('second_option_available_channels'.Chat::$chat_id, $second_option_channel,now()->addMinutes(5));
              $this->sendMessage();
           }
           elseif($this->count<=3){
              $initial_date = $this->final_date;
              $this->handle($this->coming_package_id,$this->page_number,1,$this->inline,$this->added_day,$initial_date);
           }else{
              GeneralService::answerCallBackQuery('there is no nearby available days');
           }
        }
    }

    private function findSecondOptionFreeChannels($splited_channel_id)
    {
        $channel_with_ocuurence = array_count_values($splited_channel_id->pluck('id'));
        $unique                 = $splited_channel_id->keyBy('id')->unique();
        $channel = collect([]);
        foreach($channel_with_ocuurence as $occurence => $id){
           if($occurence < $unique->get($id)){
             $channel->push($id);
           }
        }
        return $channel;
    }

    private function splitChannelId($taken_channels)
    {  
      $collection =collect([]);  
      foreach($taken_channels as $channel){
         foreach($channel as $key => $value){
                  $collection->push($value);
         }
      }
      return $collection->toArray();
    }

    private  function firstOptionAvailableChannels( $taken_channel_id)
    {
       return BotPackageRepository::finAvailableChannels($taken_channel_id);
    } 

   private function sendMessage()
   {
     $text     = $this->makeTextMessage();
     $keyboard = $this->makeKeyboard();
   }

   private function makeTextMessage()
   {    
        $initial_date  = Carbon::parse($this->initial_date)->formatLocalized('%d %b %Y');
        $final_date    = Carbon::parse($this->final_date)->formatLocalized('%d %b %Y');  
        return '<b> ---- Available Days ---- </b>'."\n".
               '<b> Initial Posting Date:</b>'.$initial_date."\n".
               '<b> Final Posting Date:</b>'.$final_date."\n";
   }

   private function makeKeyboard()
   {
            
   }

}