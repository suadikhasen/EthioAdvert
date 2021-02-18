<?php


namespace App\TelgramBot\Classes\ChannelOwner;


use App\TelgramBot\Common\Services\Advertiser\ViewAdvertService;
use App\TelgramBot\Common\Services\Messages\ChannelMessages;
use App\TelgramBot\Database\ChannelRepository;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class PerDayPosts
 * @package App\TelgramBot\Classes\ChannelOwner
 */
class PerDayPosts
{

    /**
     * @var Keyboard
     */
    private $channels_inline_keyboard;
    /**
     * @var string
     */
    private $channels_text_message;
    /**
     * @var string
     */
    private $per_day_text_message;

    /**handle the incoming request
     * @param bool $isCommand
     * @throws TelegramSDKException
     */
    public function handle($isCommand=true)
   {
      if ($isCommand)
          $this->processCommand();
      else
          $this->processQuestion();
   }

    /**
     *handel the request which come from Per Day Posts Button
     * @throws TelegramSDKException
     */
    private function processCommand()
    {
      $text_message  = '➡️ <b> Currently the number of posts on each channel is 1.</b>';
      Chat::sendTextMessage($text_message);
    }

    /**
     *
     * @throws TelegramSDKException
     */
    private function processQuestion()
    {
      $this->make_per_day_text_message();
      Chat::sendTextMessage($this->per_day_text_message);
    }

    /**
     * @throws TelegramSDKException
     */
    private function sendListOfChannels()
    {
       $this->makeKeyboard();
       $this->makeTextMessage();
       Chat::sendTextMessageWithInlineKeyboard($this->channels_text_message,$this->channels_inline_keyboard);

    }

    /**
     *prepare inline keyboard for list of channels
     */
    private function makeKeyboard()
    {
        $channels =  ChannelRepository::allChannelsOfAuser(Chat::$chat_id);
        $this->channels_inline_keyboard = Keyboard::make()->inline();
        foreach ($channels as $channel)
        {
            $this->channels_inline_keyboard = $this->channels_inline_keyboard->row(Keyboard::inlineButton([
                  'text'           =>  $channel->name,
                  'callback_data'  =>  'per_day_posts/'.$channel->id,
            ]));
        }

    }

    /**
     *prepare text message for list of  channels
     */
    private function makeTextMessage()
    {
       $this->channels_text_message = '<b>Please Select A Channel</b>';
    }

    private function make_per_day_text_message()
    {
        $channel = ChannelRepository::getChannelById(ViewAdvertService::getIDFromViewKeyboard());
        $this->per_day_text_message = 'current per day post for '.$channel->name.'is '.$channel->per_day_post."\n".
            'click below to change'."\n".
            '/change_per_day_post_'.$channel->id;
    }
}
