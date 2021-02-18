<?php


namespace App\TelgramBot\Classes\Earnings;

use App\TelgramBot\Database\ChannelRepository;
use App\TelgramBot\Database\EarningRepository;
use App\TelgramBot\Object\Chat;

/**
 * Class MonthlyEarning
 * @package App\TelgramBot\Classes\Earnings
 */
class MonthlyEarning
{
    /**
     *
     */
    public function handle(): void
    {
        if (ChannelRepository::checkExistenceOfAchannel()){
            $this->monthlyEarningOfUser();
        }else{
            Chat::sendTextMessage('You Dont Have A Channel Please Add A Channel');
        }
    }

    /**
     *
     */
    private function monthlyEarningOfUser(): void
    {
        $text=$this->makeMonthlyEarningMessage();
        Chat::sendTextMessage($text);
    }

    private function makeMonthlyEarningMessage()
    {
        $channels = ChannelRepository::allChannelsOfAuser(Chat::$chat_id);
        $text = '<b> <i> Monthly Earning Information: </i> </b>'."\n"."\n";
        $total=0.0;
        foreach($channels as $channel){
            $earning=EarningRepository::singleChannelMonthlyEarning($channel->channel_id);
            $text.='<strong> On Channel '.$channel->name.'</strong>'.': '.$earning." ETB \n"."\n"; 
            $total+= $earning;
        }
        $text .= '<strong>Total:</strong>' .$total." ETB". "\n";
        return $text;
    }
}
