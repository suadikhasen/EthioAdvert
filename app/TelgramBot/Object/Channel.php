<?php


namespace App\TelgramBot\Object;

use App\Channels;
use App\TelgramBot\Common\Pages;
use App\TelgramBot\Object\Chat;
/**
 * Class Channel
 * @package App\TelgramBot\Object
 */
class Channel
{
    /**
     * @var
     */
    public  $channel_name;
    public  $channel_id;
    public  $channel_member_amount;
    public  $channel_chat;
    public  $type;
    public  $username;

    /**
     * Channel constructor.
     * @param $username
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function __construct($username)
   {
       $this->username = $username;
       $this->channel_chat = Chat::$bot->getChat([
           'chat_id' => $username
       ]);
       $this->channel_id = $this->channel_chat->getId();
       $this->channel_name = $this->channel_chat->getTitle();
       $this->type = $this->channel_chat->getType();

       $this->channel_member_amount = Chat::$bot->getChatMembersCount([
           'chat_id' => $username
       ]);


   }

   public function register()
   {
       if ($this->channel_chat){
     if ($this->isChannel()){
        if ($this->channel_member_amount > 1000){
            Channels::create([
                'channel_id'       => $this->channel_id,
                'username'         => $this->username,
                'name'             => $this->channel_name,
                'channel_owner_id' => Chat::$chat_id,
                'number_of_member' => $this->channel_member_amount,


            ]);

            Pages::textMessageWithMenuButton('your channel is registered please add @adddvert_bot as admin in your channel and we will approve you soon');
            Chat::deleteTemporaryData();
        }else{
            Pages::textMessageWithMenuButton('your channel member is less than 1000 currently it can not be registered Please try later thank you !');
        }
     }else{
         Pages::textMessageWithMenuButton('the register chat must be channel the chat you send for us is'.$this->type);
     }
   }else{
           Pages::textMessageWithMenuButton('Please Insert Correct Channel Id');
       }

   }

    /**
     * @return mixed
     */
    public function getChannelName()
    {
        return $this->channel_name;
    }

    /**
     * @return mixed
     */
    public function getChannelId()
    {
        return $this->channel_id;
    }

    /**
     * @return mixed
     */
    public function getChannelMemberAmount()
    {
        return $this->channel_member_amount;
    }

    /**
     * @return mixed
     */
    public function getChannelChat()
    {
        return $this->channel_chat;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

   public function removeChannel()
   {

   }

   public function isChannel()
   {
     if ($this->type == 'channel')
     {
         return true;
     }
     return false;
   }

   public function hassAccessToOurBot()
   {

   }

   public function allChannels()
   {

   }



}
