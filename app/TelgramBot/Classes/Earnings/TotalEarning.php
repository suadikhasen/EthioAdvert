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
        $total_earning = EarningRepository::totalEarningOfUserByChannel();
        $text = '<strong>Total Earning Information:</strong>'."\n";
        $total = 0.0;
        foreach ($total_earning as $channel_earning){
            $total += $channel_earning->total_channel_earning;
            $text .= '<strong>'.$channel_earning->channelsName->name . ':</strong>'.$channel_earning->total_channel_earning."\n";
        }
        $text .= '<strong>Total:</strong>' .$total. "\n";
        Chat::sendTextMessage($text);
    }



}
