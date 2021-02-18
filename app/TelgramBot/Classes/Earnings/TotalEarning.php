<?php

namespace App\TelgramBot\Classes\Earnings;

use App\TelgramBot\Database\ChannelRepository;
use App\TelgramBot\Database\EarningRepository;
use App\TelgramBot\Extend\channels;
use App\TelgramBot\Object\Channel;
use App\TelgramBot\Object\Chat;

class TotalEarning
{

    public function handle($isCommand = false): void
    {
       if (ChannelRepository::checkExistenceOfAchannel()){
         $this->totalEarningOfUser();
       }else{
           Chat::sendTextMessage('You Dont Have A Channel Please Add A Channel');
       }
    }


    private function totalEarningOfUser(): void
    {  
        $text=$this->makeTotalEarningMessage();
        Chat::sendTextMessage($text);
    }

    private function makeTotalEarningMessage()
    {
        $channels = ChannelRepository::allChannelsOfAuser(Chat::$chat_id);
        $text = '<b> <i>  Total Earning Information:</i> </b>'."\n"."\n";
        $total=0.0;
        foreach($channels as $channel){
            $earning=EarningRepository::singleChannelEarning($channel->channel_id);
            $text.='<strong> On Channel '.$channel->name.'</strong>'.': '.$earning." ETB \n"."\n"; 
            $total+= $earning;
        }
        $text .= '<strong>Total:</strong>' .$total." ETB". "\n";
        return $text;

    }



}
