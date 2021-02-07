<?php


namespace App\TelgramBot\Classes\Channels;

use App\TelgramBot\Common\GeneralService;
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
           if(!ChannelRepository::checkExistenceOfAchannel(Chat::$chat_id)){
              Chat::sendTextMessage('you have no channel');
           }else{
            Chat::createQuestion('remove_channel','click_channel_name');
            Pages::removeChannelsPage();
           }
          
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
        if ($response->answer === null){
            Chat::createAnswer($response->id);
            $this->sendConfirmationQuestion();
        }else{
           if (Chat::getCallBackQuery()->getData() === 'NO')
           {
               $this->cancelConfirmation();
           }elseif(Chat::getCallBackQuery()->getData() === 'YES'){
               $channel = ChannelRepository::findByNameAndChatId(Chat::$chat_id,$response->answer);
               ChannelRepository::updateRemoveStatus($channel->channel_id,true);
               Chat::deleteTemporaryData();
               Chat::sendEditTextMessage("your channel removed successfully",null,GeneralService::getChatIdFromCallBack(),GeneralService::getMessageIDFromCallBack());
               Pages::channelOwnerPage();
           }else{
               Chat::sendTextMessage('please use inline keyboard');
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
                'text'          => 'YES',
                'callback_data' => 'YES'
            ])
            );
        Chat::sendTextMessageWithInlineKeyboard('Are You Sure You Want Remove '.Chat::$text_message.' channel',$keyboard);
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
