<?php

namespace App\TelgramBot\Classes\Channels;

use App\TelgramBot\Common\Pages;
use App\TelgramBot\Object\Channel;
use App\TelgramBot\Object\Chat;
use App\Temporary;

class AddChannel
{

    /**
     * @param bool $isCommand
     * @return void
     */
    public function handle($isCommand=false)
    {

        if ($isCommand)
        {
            $question = Chat::createQuestion('Add Channel','username');
            if ($question){
                Pages::textMessageWithMenuButton('please insert you channel username dont forget @ symbol');
            }else{
                Chat::sendTextMessage('something went wrong');
            }

        }else{
            $response = Chat::lastAskedQuestion();
            $this->processQuestion($response);
        }

    }

    public function processQuestion($response): void
    {
        if ($response->answer === null)
        {
            switch ($response->question){
                case 'username':
                    Chat::createAnswer($response->id);
                    (new Channel(Chat::$text_message))->register();
                    break;

            }
        }else{
            Pages::textMessageWithMenuButton('something went wrong');
            Chat::deleteTemporaryData();
        }
    }

}
