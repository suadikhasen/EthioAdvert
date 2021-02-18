<?php

namespace App\TelgramBot\Classes\Advertiser\Common;

use App\TelgramBot\Database\PackageRepositoryService;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Keyboard\Keyboard;

class HowManyDays
{

    public function sendMessage()
    {
        $days = PackageRepositoryService::retriveuniqueDays();
        $inline_keyboards = $this->makNumberOfDaysInlineKeyboard($days);
        $text = '⬇️<b> select number of days you want your advert live </b>'."\n";
        Chat::sendTextMessageWithInlineKeyboard($text,$inline_keyboards);
    }

    private function makNumberOfDaysInlineKeyboard($days)
    {  
        $number_of_days_key_board = Keyboard::make()->inline();
        foreach($days as $day){
            $number_of_days_key_board=$number_of_days_key_board->row(Keyboard::inlineButton([
                'text'            => $day,
                'callback_data'  => 'select_number_of_days/'.$day
            ]));
        }
       return $number_of_days_key_board; 
    }

    public function validateDay()
    {
        if (in_array(Chat::$text_message, PackageRepositoryService::retriveuniqueDays()->toArray())) {
            return true;
        }
        return false;
    }
}
