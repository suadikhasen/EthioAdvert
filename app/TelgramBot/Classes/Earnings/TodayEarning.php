<?php


namespace App\TelgramBot\Classes\Earnings;


use App\TelgramBot\Database\ChannelRepository;
use App\TelgramBot\Database\EarningRepository;
use App\TelgramBot\Object\Chat;

class TodayEarning
{
  public function handle()
  {
      if (ChannelRepository::checkExistenceOfAchannel()){
          $this->todayEarningOfUser();
      }else{
          Chat::sendTextMessage('You Dont Have A Channel Please Add A Channel');
      }
  }

    private function todayEarningOfUser()
    {
        $text = $this->makeTodayEarningMessage();
        Chat::sendTextMessage($text);
    }

    private function makeTodayEarningMessage()
    {
        $channels = ChannelRepository::allChannelsOfAuser(Chat::$chat_id);
        $text = '<b> <i>  Today Earning Information: </i> </b>'."\n"."\n";
        $total=0.0;
        foreach($channels as $channel){
            $earning=EarningRepository::singleChannelTodayEarning($channel->channel_id);
            $text.='<strong> On Channel '.$channel->name.'</strong>'.': '.$earning." ETB \n"."\n"; 
            $total+= $earning;
        }
        $text .= '<strong>Total:</strong>' .$total." ETB". "\n";
        return $text;
    }
}
