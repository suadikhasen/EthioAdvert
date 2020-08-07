<?php


namespace App\TelgramBot\Extend;
use App\Channels as ModelChannel;
use App\TelegramPost;
use App\TelgramBot\Common\Pages;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Mixed_;
use Telegram\Bot\Keyboard\Keyboard;

class channels
{
  public function __construct()
  {
      $chat = ModelChannel::where('chat_id',Chat::$chat_id)->doesntExist();
      if ($chat)
      {
          Chat::sendTextMessage('You Have No Channel please add channels in order to see earning reports');
          return;
      }
  }

  public function listOfChannels():Collection
  {
      return ModelChannel::where('chat_id',Chat::$chat_id)->get();
  }

  public function singleChannelEarning($channel_id)
  {
      return TelegramPost::where('channel_id',$channel_id)->sum('earning');
  }

  public function  sendListOfUsersChannel($channels): void
  {
      $keyboard = Keyboard::make(['resize_keyboard' => 'true']);
      foreach ($channels as $channel){
       $keyboard = $keyboard->row(Keyboard::inlineButton(['text' => $channel->name,'callback_data' => 'select_channel'.$channel->channel_id]));
      }
      $keyboard = $keyboard->row(Keyboard::inlineButton(['text' => 'from All Channels','callback_data' => 'select_channel'.'all_channel']));
      Pages::$text = 'Select How Do You Want To Know Your Earning';
      Chat::createQuestion('Total Earning','Select Channel');
      Pages::sendMessage($keyboard);

  }

  public function calculateTotalEarningOfAllChannels()
  {
      return TelegramPost::where('channel_owner_id',Chat::$chat_id)->sum('earning');
  }

  public function getChannelIdFromCallBackData()
  {
      if (Str::startsWith(Chat::$text_message,'select_channel'))
      {
          return Str::after(Chat::$text_message,'select_channel');
      }

      return 'no_channel_id';
  }

}
