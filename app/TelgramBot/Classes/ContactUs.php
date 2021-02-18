<?php

namespace App\TelgramBot\Classes;

use App\TelgramBot\Object\Chat;

class ContactUs
{  
    protected $contact_message;

    public function __construct()
    {
        $this->contact_message=  $this->makeContactMessage();
        
    }

    public function sendMessage()
    {
        Chat::sendTextMessage($this->contact_message);
    }

    private function makeContactMessage()
    {
       return '<b>----------contact us----------</b>'."\n\n".
              '➡️ <b>Phone Number : 0994920163</b>'."\n\n".
              '➡️ <b>Telegram : @ethio_tg_ad</b>'."\n\n".
              '➡️ <b>Telegram : @ethio_ad_support_bot</b>'."\n\n";
    }
}