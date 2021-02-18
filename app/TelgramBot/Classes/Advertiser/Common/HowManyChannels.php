<?php

namespace App\TelgramBot\Classes\Advertiser\Common;
use App\TelgramBot\Object\Chat;

class  HowManyChannels
{   

    public function sendMessage($number_of_channels)
    {
        $text_message = 'We have '.$number_of_channels.' channels'.'available for you please send number of channels that are not greaterthan this number';
        Chat::sendTextMessage($text_message);
    }
}