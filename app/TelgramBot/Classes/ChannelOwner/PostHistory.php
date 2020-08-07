<?php

namespace App\TelgramBot\Classes\ChannelOwner;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\TelegramPostRepository;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class PostHistory
 * @package App\TelgramBot\Classes\ChannelOwner
 */
class PostHistory
{
    /**
     * @var
     */
    private $list_of_posts;

    /**
     * @var string
     */
    private $text = '<strong><i>List Of Posts</i></strong>';
    /**
     * @var Keyboard
     */
    private $keyboard = null;
    /**
     * @var int
     */
    private $page_number;

    /**handle the incoming request
     * @param int $page_number
     * @param bool $inline
     * @throws TelegramSDKException
     */
    public function handle(int $page_number,bool  $inline)
    {
       $this->page_number = $page_number;
       $this->list_of_posts = TelegramPostRepository::findListOfAdverts(Chat::$chat_id,GeneralService::default_number_of_posts,$page_number);
       $this->list_of_posts = json_decode($this->list_of_posts);
       $this->makeTextForListOfPromotions($this->list_of_posts);
       $this->makeKeyBoard($this->list_of_posts);
       $this->sendMessage($inline);
    }


    /**
     *make keyboard message for lists
     * weather both  ,previous or next keyboards
     * @param $list
     */

    private function makeKeyBoard($list)
    {
        if ($list->prev_page_url === null){
            if ($list->next_page_url !== null)
                $this->keyboard = $this->nextKeyBoard();
        }elseif($list->next_page_url !== null)
            $this->keyboard = $this->bothKeyboard();
        else
            $this->keyboard = $this->previousKeyboard();
    }

    /**send message
     * @param bool $inline
     * @throws TelegramSDKException
     */

    private function sendMessage(bool $inline)
    {
       if ($inline)
           Chat::sendEditTextMessage($this->text,$this->keyboard,Chat::$chat_id,GeneralService::getMessageIDFromCallBack());
       else
           Chat::sendTextMessageWithInlineKeyboard($this->text,$this->keyboard);
    }

    /**make text message of list of promotions
     * @param $list
     */
    private function makeTextForListOfPromotions($list)
    {
        $no = $this->postNumbers();
        foreach ($list->data as $advert){
            $this->text = $this->text.'<strong>'.$no.', '.$advert->adverts->name_of_the_advert.'</strong>'."\n".
                '<strong>Earning:</strong>'.$advert->earning."\n".
                '<strong>Number Of View:</strong>'.$advert->number_of_view."\n".
                '<strong>Posted On: </strong>'.$advert->channelsName->name.' channel'."\n".
                '<strong>Status: </strong>'.$this->activeStatus($advert->active_status);
            $no++;
        }

    }


    /**make next keyboard
     * @return Keyboard
     */
    private function nextKeyBoard():Keyboard
    {
        return  Keyboard::make()->inline()->row(Keyboard::inlineButton([
            'text'           =>     'Next',
            'callback_data'  =>     'post_history/'.($this->page_number+1)
        ]));
    }

    /**make previous keyboard
     * @return Keyboard
     */
    private function previousKeyboard():Keyboard
    {
        return Keyboard::make()->inline()->row(Keyboard::inlineButton([
            'text'           =>     'Previous',
            'callback_data'  =>     'post_history/'.($this->page_number-1)
        ]));
    }

    /**make both previous and next keyboard
     * @return Keyboard
     */
    private function bothKeyboard():Keyboard
    {
        return Keyboard::make()->inline()->row(
            Keyboard::inlineButton([
                'text'           =>     'Previous',
                'callback_data'  =>     'post_history/'.($this->page_number-1),
            ]),Keyboard::inlineButton([
            'text'           =>     'Next',
            'callback_data'    =>     'post_history/'.($this->page_number+1),
        ]));
    }

    /**
     * @return int
     */
    private function postNumbers():int
    {
        return (int) ((($this->page_number-1)*GeneralService::default_number_of_posts)+1);
    }

    /**
     * @param bool $active_status
     * @return string
     */
    private function activeStatus(bool $active_status):string
    {
        if ($active_status)
            return  'active';
        else
            return 'posted';
    }

}
