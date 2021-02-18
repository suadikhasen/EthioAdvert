<?php

namespace App\TelgramBot\Classes;

use App\TelgramBot\Object\Chat;

class ChannelOwner
{
    public function __construct()
    {
        if (isRegistered(Chat::$chat_id)){
            return;
        }
    }


    public function handle($isCommand=false)
    {

        if ($isCommand)
        {
            $question = Chat::createQuestion('Channel Owner','full_name');
            if ($question){
                Chat::sendTextMessage('please insert your full name');
            }else{
                Chat::sendTextMessage('something went wrong');
            }

        }else{
            $response = Chat::lastAskedQuestion();
            $this->processQuestion($response);
        }
    }

    private function processQuestion($response): void
    {
        if ($response->answer === null)
        {
            switch ($response->question){
                case 'full_name':
                    Chat::sendInlineKeyBoardForPhone('Send Your Phone Number');
                    Chat::createAnswer($response->id);
                    Chat::createQuestion('Channel Owner','phone_number');
                    break;
                case 'phone_number':
                    Chat::Register('Channel Owner');
                    Chat::createAnswer($response->id);

            }
        }else{
            Chat::sendTextMessage('sorry something went wrong try again');
        }


    }
}
