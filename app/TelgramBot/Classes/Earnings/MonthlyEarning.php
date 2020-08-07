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
        $total_earning = EarningRepository::monthlyEarningOfUserByChannel();
        $text = '<strong>Monthly Earning Information:</strong>'."\n";
        $total = 0.0;
        foreach ($total_earning as $channel_earning){
            $total += $channel_earning->total_channel_earning;
            $text .='<strong>'.$channel_earning->channelsName->name.':</strong>'.$channel_earning->total_channel_earning."\n";
        }
        $text .= '<strong>Total:</strong>'.$total."\n";
        Chat::sendTextMessage($text);
    }
}
