<?php


namespace App\TelgramBot\Object;

use App\Channels;
use App\TelgramBot\Common\Pages;
use App\TelgramBot\Database\Admin\ChannelRepository;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Exceptions\TelegramSDKException;
use App\Services\Common\TelegramBot;

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
       try{
        $this->channel_chat = Chat::$bot->getChat([
            'chat_id' => $username
        ]);
       }catch(TelegramSDKException $exception){
          Chat::sendTextMessage(' ❌ channel username not found please try again ❌');
          exit;
       }
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
        if(Channels::where('channel_id',$this->channel_id)->exists()){
            Chat::sendTextMessage(' ❌ your channel already exist ❌');
            exit;
          }
        if ($this->channel_member_amount > 0){
            Channels::create([
                'channel_id'       => $this->channel_id,
                'username'         => $this->username,
                'name'             => $this->channel_name,
                'channel_owner_id' => Chat::$chat_id,
                'number_of_member' => $this->channel_member_amount,
            ]);
            Chat::deleteTemporaryData();
            Pages::textMessageWithMenuButton('✅✅your channel  registered successfully !! ✅✅'."\n".'❕ please add @EthioAdvertisementBot as admin in your channel and we will approve you soon ❕');
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
