<?php

namespace App\TelgramBot\Classes;
use Telegram\Bot\Api;
use App\TelgramBot\Object\Chat;
/**
 * Class Advertiser
 * @package App\TelgramBot\Classes
 */
class Advertiser
{

  /*
    public function __construct()
    {
        if (isRegistered(Chat::$chat_id)){
            Chat::sendTextMessage(
                 'you are already registered'
            );

            return;

        }
    }
    */

    /**
     * @param bool $isCommand
     * @return bool
     */
    public function handle($isCommand=false)
    {

        if ($isCommand)
        {
           $question = Chat::createQuestion('Advertiser','full_name');
           if ($question){
               Chat::sendTextMessage('please send your full name');
           }else{
               Chat::sendTextMessage('something went wrong');
           }

        }else{
            $response = Chat::lastAskedQuestion();
            $this->processQuestion($response);
        }

    }

    /**
     * @param $response
     */
    public  function processQuestion($response): void
    {
        if ($response->answer === null)
        {
            switch ($response->question){
                case 'full_name':
                    Chat::createAnswer($response->id);
                    Chat::sendInlineKeyBoardForPhone('send Your  Phone Number');
                    Chat::createQuestion('Advertiser','phone_number');
                    break;
                case 'phone_number':
                    Chat::createAnswer($response->id);
                    Chat::Register('Advertiser');
            }
        }

//        Chat::sendTextMessage('sorry something went wrong try again');
    }

}
