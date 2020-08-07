<?php


namespace App\TelgramBot\Classes\Channels;


use App\TelgramBot\Common\Pages;
use App\TelgramBot\Database\ChannelRepository;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class RemoveChannel
 * @package App\TelgramBot\Classes\Channels
 */
class RemoveChannel
{
    /**
     * @param bool $isCommand
     */
    public function handle(bool $isCommand = false)
   {
       if ($isCommand){
          Chat::createQuestion('remove_channel','click_channel_name');
          Pages::removeChannelsPage();
       }else{
           $this->processQuestion(Chat::lastAskedQuestion());
       }

   }

    /**
     * @param $response
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function processQuestion($response): void
    {
        if ($response->answers === null){
            Chat::createAnswer($response->id);
            $this->sendConfirmationQuestion();
        }else{
           if (Chat::getCallBackQuery()->getData() === 'NO')
           {
               $this->cancelConfirmation();
           }
        }
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function sendConfirmationQuestion(): void
    {
        $keyboard = Keyboard::make()->inline()->row(
            Keyboard::inlineButton([
            'text'          => 'NO',
            'callback_data' =>  'NO'
            ]),
            Keyboard::inlineButton([
                'text'          => ' "\U00002716" Yes',
                'callback_data' => 'Yes'
            ])
            );
        Chat::sendTextMessageWithInlineKeyboard('Are You Sure You Want Remove '.Chat::$text_message,$keyboard);
    }

    /**
     *accept confirmation and remove the channel
     */
    private function acceptConfirmation()
    {
        ChannelRepository::removeChannelsOfAuserByName(Chat::lastAskedQuestion()->answer,Chat::getCallBackQuery()->getFrom()->getId());
        Chat::$bot->editMessageText([
            'text'                 => 'Your Channel Is Removed',
            'inline_message_id'   => Chat::getCallBackQuery()->getInlineMessageId()
        ]);
        Chat::deleteTemporaryData();
        Pages::channelOwnerPage();
    }


    /**
     *send canceled information for removing channel
     */
    private function cancelConfirmation()
    {
       Chat::$bot->editMessageText([
          'text'                 => 'channel removing confirmation canceled',
           'inline_message_id'   => Chat::getCallBackQuery()->getInlineMessageId()
       ]);
    }
}
