<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Services\Common\TelegramBot;
use App\TelegramPost;
use App\TelgramBot\Database\Admin\AdvertRepository;
use App\TelgramBot\Database\Admin\ChannelRepository;
use App\TelgramBot\Database\LevelOfChannelReopoksitory;
use App\TelgramBot\Database\PackageRepositoryService;
use App\TelgramBot\Database\Admin\TelegramPostRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdvertPostingController extends Controller
{
    private $advert;
    private $list_of_channels;
    private $filtered_list_of_channels;
    private $is_new;

    public function __construct($advert_id)
    {
        $this->advert = AdvertRepository::findAdvert($advert_id);

    }
    
    public function post()
     {
       if($this->checkPreRequestOfAdvert()){
          $this->isNewPackage();
          $this->processPosting();
          $this->postAdvert();
          TelegramBot::sendNotificationForAdvertiser($this->advert,$this->filtered_list_of_channels,$this->is_new);
          return back()->with('success_message','advert posted successfully .');

       }else{
         return back()->with('error_message','advert dont full fill the rquirement .');
       }
     }

     private function processPosting()
     {
         if(self::$is_new){
          $this->list_of_channels = ChannelRepository::listOfChannelsByChannelId($this->advert->level_id);
          $this->filtered_list_of_channels = $this->filterChannels();
         }else{
           $this->filtered_list_of_channels = $this->previousChannels();
         }
     }

     private function previousChannels()
     {
       return TelegramPost::getChannelsOfAdvert($this->advert->id);
     }

     private  function postAdvert()
     {
       foreach($this->filtered_list_of_channels as $channel){
         $message = TelegramBot::sendAdvert($channel->channel_id,$this->advert);
         $posts = TelegramPostRepository::createTelegramPost($channel->channel_id,$channel->channel_owner_id,$this->advert->id,$message->messageId);
         $update = AdvertRepository::updateStatus($this->advert->id,2);
         TelegramBot::sendNotificationForChannelOwner(true,$this->advert,$posts->earning,$channel);
       }
       
     }

     private function filterChannels()
     {   
         $filtered_channels = array();
         $control = $this->advert->number_of_channel;
         $count = 0;
         foreach($this->list_of_channels as $channel){
           $number_of_opened_adverts = ChannelRepository::countOpenedPotsOfChannel(
           $channel->channel_id,
           Carbon::parse($this->advert->gc_calendar_initial_date),
           Carbon::parse($this->advert->gc_calendar_final_date));

           if($number_of_opened_adverts < $channel->per_day_posts && TelegramBot::checkChannelAuthorization($channel->channel_id)){
            $filtered_channels[$channel->channel_id] = $channel;
            $count++;
            if($count > $control ){
                break;
            }
           }

         }
         return $filtered_channels;
     }

     private function checkPreRequestOfAdvert()
     {
         if($this->advert->approve_status && $this->advert->payment_status && ($this->advert->active_status === 1 || $this->advert->active_status === 3))
           return true;

        return false;
     }

     private function isNewPackage()
     {
       if($this->advert->active_status === 1)
          self::$is_new = true;
       else   
         self::$is_new = false;
     }
}
