<?php


namespace App\TelgramBot\Classes\ChannelOwner;


use App\Channels;
use App\TelgramBot\Common\Services\CacheService;
use App\TelgramBot\Common\Services\ChannelOwner\ChannelService;
use App\TelgramBot\Common\Services\Messages\ChannelMessages;
use App\TelgramBot\Object\Chat;

class ChangePerDayPost
{
    private $chat_id;
    private $channel_id;
    private $is_command;

    public function __construct($channel_id, $chat_id,$isCommand)
   {
     if (!ChannelService::channelAuthorization($channel_id,$chat_id)){
         ChannelMessages::notAuthorize();
         return;
     }else{
         $this->channel_id = $channel_id;
         $this->chat_id    = $chat_id;
         $this->is_command = $isCommand;
         $this->handle();
     }
   }

    private function handle()
    {
      if ($this->is_command)
          $this->processCommand();
      else
          $this->processQuestion();
    }

    private function processCommand()
    {
        Chat::createQuestion('Change_Per_Day_Post','per_day_post');
        CacheService::putPerDayPostCache($this->channel_id,$this->chat_id);
        ChannelMessages::perDayPost();
    }

    private function processQuestion()
    {
        if (is_integer(Chat::$text_message))
            $this->finishEditing();
        else
            ChannelMessages::invalidValue();

    }


    private function finishEditing()
    {
        $this->update(Chat::$text_message);
        CacheService::removePerDayPostCache($this->chat_id);
        Chat::deleteTemporaryData();
        ChannelMessages::updatePerDayPost();
    }

    private function update(int $per_day_post)
    {
        Channels::where('id',$this->channel_id)->update([
            'per_day_post'  => $per_day_post
        ]);
    }
}
