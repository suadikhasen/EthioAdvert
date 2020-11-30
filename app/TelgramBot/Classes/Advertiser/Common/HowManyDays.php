<?php

namespace App\TelgramBot\Classes\Advertiser\Common;

use App\TelgramBot\Database\PackageRepositoryService;
use App\TelgramBot\Object\Chat;

class HowManyDays
{

    public function sendMessage()
    {
        $days = PackageRepositoryService::retriveuniqueDays();
        $text = 'Send number of days the advert is live given below' . "\n";
        foreach ($days as $day) {
            $text .= "send " . $day . " for " . $day . " days" . "\n";
        }
        $text .= "\n" . 'dont send numbers not listed on the above';
        Chat::sendTextMessage($text);
    }

    public function validateDay()
    {
        if (in_array(Chat::$text_message, PackageRepositoryService::retriveuniqueDays()->toArray())) {
            return true;
        }
        return false;
    }
}
