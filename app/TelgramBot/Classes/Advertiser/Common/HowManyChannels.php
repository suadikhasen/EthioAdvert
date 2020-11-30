<?php

namespace App\TelgramBot\Classes\Advertiser\Common;
use App\TelgramBot\Object\Chat;

class  HowManyChannels
{   
    public $maximum_channels = 10;

    public function sendMessage()
    {
        $text_message = 'We have '.$this->maximum_channels.' channels'.'available for you please send number of channels that are not greaterthan this number';
        Chat::sendTextMessage($text_message);

    }
}