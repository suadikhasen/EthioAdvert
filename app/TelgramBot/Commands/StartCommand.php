<?php

namespace App\TelgramBot\Commands;
use App\TelgramBot\Common\Pages;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;
/**
 * Class StartCommand
 * @package App\TelgramBot\Commands
 */
class StartCommand extends Command
{

    /**
     * @var $name
     */
    protected  $name = 'start';

    // protected  $chat_id;
    /**
     * @var string
     */
    protected $description = 'used do start a bot';
    /**
     * @var Api
     */
    protected $bot;

    /**
     * {@inheritdoc}
     * @throws TelegramSDKException
     */
    public function handle()
    {   
        Chat::$isCommand = true;
        Chat::$update = $this->getUpdate();
        Chat::$chat_id   = Chat::$update->getMessage()->getChat()->getId();
       if (isRegistered(Chat::$chat_id)){
          if (userType() === 'Advertiser'){
              Pages::advertiserPage();
          }else{
              Pages::channelOwnerPage();
          }
        return;
       }else{
           $keyboard = [
               [
                   'Advertiser',
                   'Channel Owner',
               ]
           ];
           $reply_mark_up = Keyboard::make([
               'keyboard' => $keyboard,
               'resize_keyboard' => true,
           ]);

           $this->replyWithMessage([
               'text'           =>  '<b> 😊 welcome !! 😊 </b>'."\n".  ' <b> choose the option to register </b>',
               'reply_markup'   =>   $reply_mark_up,
               'parse_mode'      =>  'HTML',
           ]);

       }

    }
}
