<?php


namespace App\TelgramBot\Classes\Channels;

use App\TelgramBot\Common\Pages;
use App\TelgramBot\Database\ChannelRepository;
use App\TelgramBot\Object\Chat;

/**
 * Class AllChannel
 * @package App\TelgramBot\Classes\Channels
 */
class AllChannel
{
    /**
     * @param bool $isCommand
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function  handle($isCommand=false): void
    {
      if ($isCommand)
      {
          if (ChannelRepository::checkExistenceOfAchannel()){
              Chat::createQuestion('All_Channel','description_of_channel');
              Pages::listOfChannelsPage();
          }else{
              Chat::sendTextMessage('There Is No Channels Registered In Your Account');
          }

      }else{
        $response = Chat::lastAskedQuestion();
        $this->processQuestion($response);
      }
  }

    /**
     * @param $response
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function processQuestion($response): void
    {
        if ($response->answer === null){
          $this->sendDetailAboutChannel();
        }else{
            Pages::textMessageWithMenuButton('something went wrong');
            Chat::deleteTemporaryData();
        }
    }

    /**send detail information about the channel
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function sendDetailAboutChannel(): void
    {
        $channel = ChannelRepository::searchChannelByName(Chat::$text_message);
        if ($channel->approved_status){
            $approved_status = 'Approved';
        }else{
            $approved_status = 'Not Approved';
        }
        Chat::sendTextMessage(
            '<strong> Channel  `'.$channel->name.'` Information </strong>'."\n".
            '<strong>Username:</strong>'.$channel->username."\n".
            '<strong>Number Of Member:</strong>'.$channel->number_of_member."\n".
            '<strong>Approved Status:</strong>'.$approved_status."\n"."\n".
            'The  Information May Not Be Correct It is Updated with in some day intervals'
        );
    }
}
