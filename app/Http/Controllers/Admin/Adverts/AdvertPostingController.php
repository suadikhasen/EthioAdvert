<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\EthioAdvertPost;
use App\Http\Controllers\Controller;
use App\Services\Common\TelegramBot;
use App\TelegramPost;
use App\TelgramBot\Database\Admin\AdvertRepository;
use App\TelgramBot\Database\Admin\ChannelRepository;
use App\TelgramBot\Database\Admin\TelegramPostRepository;
use App\TelgramBot\Database\AdvertsPostRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdvertPostingController extends Controller
{
    private $advert;
    private $list_of_channels;
    private $filtered_list_of_channels;
    private $is_new;
    private $message;
    private $channel;
    private $is_first_status;
    private $channels_username;

    
    
    public function post($advert_id)
     { 
       $this->advert = AdvertRepository::findAdvert($advert_id);
       if($this->checkPreRequestOfAdvert()){
          $this->channels_username = collect();
          if($this->isNewPackage()){
             $this->finishPosting(true);
          }else{
            $this->finishPosting(false);
          }
            AdvertRepository::updateStatus($this->advert->id,3);
            $this->sendNotificationForAdvertiser();
            return back()->with('success_notification','advert posted successfully');
       }else{
         return back()->with('error_message','advert dont full fill the rquirement .');
       }
     }

     private function finishPosting($is_first_status){

      foreach($this->advert->assigned_channels as $channel_id){
        $this->is_first_status  = $is_first_status;
        $this->message = TelegramBot::sendAdvert($channel_id,$this->advert);
        $this->channel = ChannelRepository::findChannel($channel_id);
        $this->channels_username->push($this->channel->username);
        TelegramBot::sendNotificationForChannelOwner($this->is_first_status,$this->advert,$this->channel);
        DB::transaction(function () {
            TelegramPost::create([
              'message_id'           =>  $this->message->message_id,
              'channel_id'           =>  $this->channel->channel_id,
              'earning'              =>  $this->advert->channel_price,
              'ethio_advert_post_id' =>  $this->advert->id,
              'channel_owner_id'     =>  $this->channel->channel_owner_id,
              'is_first'             =>  $this->is_first_status,
            ]);
        });
      }
     }

     private function sendNotificationForAdvertiser()
     {
       $text_message = '✅ <b> your advert is posted on the following channels.</b>'."\n\n";
                       foreach($this->channels_username as $username){
                         $text_message.='➡️ '.$username;
                       }
         $text_message.= "\n\n".'---------<b>Thank You For Working Us !</b>---------';    
         TelegramBot::sendNotification($text_message,$this->advert->advertiser_id);                                     
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
         if($this->advert->approve_status && 
         $this->advert->payment_status && 
         ($this->advert->active_status === 2 || $this->advert->active_status === 3) &&
          !$this->isPostedToday()){
            return true;
          }
        return false;
     }

     private function isPostedToday()
     {
       return TelegramPost::whereDate('created_at',Carbon::today())
       ->where('ethio_advert_post_id',$this->advert->id)->exists();
     }

     private function isNewPackage()
     {
       if($this->advert->active_status === 2)
          return true;
          return false;   
     }
}
