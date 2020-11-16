<?php


namespace App\TelgramBot\Object;
use App\TelgramBot\Common\Pages;
use App\Temporary;
use App\User;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

/**
 * Class Chat
 * @package App\TelgramBot\Object
 */
class Chat
{
    /**
     * @var Api
     */
    public  static  $bot;
    /**
     * @var Update|Update[]
     */
    public  static  $update;
    /**
     * @var
     */
    public static $chat;

    /**
     * @var int
     */
    public static $chat_id;
    /**
     * @var Message
     */
    public static $message ;
    /**
     * @var
     */
    public static $isCommand;
    /**
     * @var string
     */
    public static $text_message;

    /**
     * @return string
     */
    public static $photo_object;
    public const url = 'https://c80727d1.ngrok.io/api/1131610311:AAH8RZveEvPHyQxcwdEw1p8F602Ujeq0xqk';

    /**
     * @return string
     */
    public static  function getThisChatTextMessage(): string
    {
        if (self::$text_message !== null){
            return self::$text_message;
        }elseif (self::getContact() !== null){
            return self::getPhoneContact();
        }elseif (self::$message !== null && self::$message->has('photo')){
            return self::getPhoto()[0]['file_id'];
        }
       return null;
    }

    /**
     * @return string
     */
    public static function getPhoneContact()
    {
       return self::$update->getMessage()->getContact()->getPhoneNumber();
    }

    public static function getContact(): ?\Telegram\Bot\Objects\Contact
    {
        return self::$update->getMessage()->getContact();
    }

    /**
     * @return mixed
     */
    public static function checkExistenceOfLastActivity()
   {
       if (self::$update->isType('callback_query')){
           return Temporary::where('chat_id',self::getCallBackQuery()->getFrom()->getId())->exists();
       }
       return Temporary::where('chat_id',self::$chat_id)->exists();
   }

    /**
     * @return Temporary
     */
    public static function lastActivity():Temporary
    {     if (self::$update->isType('callback_query')){
             return Temporary::where('chat_id',self::getCallBackQuery()->getFrom()->getId())->latest()->first();
          }
          return Temporary::where('chat_id',self::$chat_id)->latest()->first();
    }

    /**
     *
     */
    public static  function type()
    {
        if (self::checkExistenceOfLastActivity()){
          return (self::lastActivity())->type;
        }
        return null;

    }

    /**
     * @return Temporary|null
     */
    public static function lastAskedQuestion()
    {
      if(self::checkExistenceOfLastActivity())
      {
          return self::lastActivity();
      }

      return null;
    }

    /**
     * @param $type
     * @param $question
     * @return bool
     */
    public static function  createQuestion($type, $question): bool
    {
       $create =  Temporary::create([

            'chat_id'  => self::$chat_id,
            'type'     => $type,
            'question' => $question,
        ]);

       if ($create)
       {
           return true;
       }

       return false;
    }

    /**
     * @param $message
     * @throws TelegramSDKException
     */
    public static function sendTextMessage($message)
    {
        self::$bot->sendMessage([
            'chat_id'    => self::$chat_id,
            'text'       => $message,
            'parse_mode' =>  'HTML'
        ]);

    }

    /**
     * @param $id
     */
    public static function createAnswer($id)
    {
        $question = Temporary::find($id);
        $question->update([
          'answer' => self::getThisChatTextMessage(),

        ]);

    }

    /**
     * @param string $string
     * @throws TelegramSDKException
     */
    public static function sendInlineKeyBoardForPhone(string $string)
    {
        $keyboard =[
            [
               ['text' => $string,
                'request_contact' => true,
               ]
            ]
        ];

        $markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true
        ]);
        self::$bot->sendMessage([
            'chat_id' => self::$chat_id,
            'text'    => 'press button to send the phone number',
            'reply_markup' => $markup,
        ]);

    }

    /**
     * @param $type
     * @throws TelegramSDKException
     */
    public static function Register($type):void
    {
        $temporary = Temporary::where('chat_id',self::$chat_id)->where('type',$type)->get();
        $array = Array();
        foreach ($temporary as $item){
            $array[(string)$item->question] = $item->answer;
        }
       $user = User::create([
           'chat_id' => self::$chat_id,
           'type'     => $type,
           'phone_number'  => $array['phone_number'],
           'full_name'  => $array['full_name'],

       ]);
       if ($user){
//         self::sendTextMessage('Thank You For Choosing Us');
           if($type === 'Channel Owner') {
               Pages::channelOwnerPage();
           }else{
               Pages::advertiserPage();
           }
           self::deleteTemporaryData();
         return;
       }
        self::deleteTemporaryData();
        self::sendTextMessage('Please Try Again');

    }

    /**
     *
     */
    public  static  function deleteTemporaryData(): void
    {
      Temporary::where('chat_id', self::$chat_id)->delete();
    }

    /**
     * @param string $text
     * @param Keyboard $keyboard
     * @throws TelegramSDKException
     */
    public static function sendTextMessageWithInlineKeyboard(string $text, $keyboard = null): void
    {
       self::$bot->sendMessage([
           'chat_id'       => self::$chat_id,
           'text'          => $text,
           'parse_mode'    => 'HTML',
           'reply_markup'  => $keyboard
       ]);
    }

    /**
     *return call back query object
     */
    public static function getCallBackQuery()
    {
        return self::$update->getCallbackQuery();
    }

    /**
     *get Photo Object
     */

    public static function getPhoto()
    {
        return self::$update->getMessage()->getPhoto();
    }

    public static function sendEditTextMessage(string $text_message,$keyboard=null, $chat_id,$message_id): void
    {
        self::$bot->editMessageText([
            'chat_id'           =>  $chat_id,
            'message_id'        =>  $message_id,
            'parse_mode'        => 'HTML',
            'text'              =>  $text_message,
            'reply_markup'      =>  $keyboard,

        ]);
    }

    public static function sendPhoto($chat_id,$photo,$caption = null,$keyboard = null): void
    {

     self::$bot->sendPhoto([

       'chat_id'    => $chat_id,
       'photo'      => $photo,
       'parse_mode' => 'HTML',
       'caption'    => $caption,
       'reply_markup'  => $keyboard
     ]);
    }
}
