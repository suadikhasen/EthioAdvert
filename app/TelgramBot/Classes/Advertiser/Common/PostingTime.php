<?php

namespace App\TelgramBot\Classes\Advertiser\Common;

use App\TelgramBot\Database\PackageRepositoryService;
use App\TelgramBot\Common\Classes\PaginationkeyBokard;
use App\TelgramBot\Object\Chat;
use App\TelgramBot\Common\GeneralService;
use Telegram\Bot\Keyboard\Keyboard;

class  PostingTime
{

    private $list_of_time;
    private $time_previous_page;
    private $time_next_page;
    private $time_page_number;
    private $text_message;
    private $inline_keyboard;
    private $package_id;
    private $per_page;
    private $page_number;
    private $number_of_days;
    private $inline = false;
    private $level_id;


    public  function sendMessage($per_page = 1, $page_number = 1, $number_of_days,$level_id, $inline = false)
    {   
        $this->per_page  = $per_page;
        $this->page_number = $page_number;
        $this->number_of_days = $number_of_days;
        $this->inline  = $inline;
        $this->level_id = $level_id;
        $this->finishSending();
    }

    private function finishSending()
    {
        $this->assignFields();
        $this->makeTimeTextMessage();
        $this->makeTimeSelectionKeyboard();
        $this->makePagination();
        if ($this->inline) {
            Chat::sendEditTextMessage($this->text_message, $this->inline_keyboard, Chat::$chat_id, GeneralService::getInlineMessageID());
            GeneralService::answerCallBackQuery('Page ' . $this->time_page_number . 'displayed');
        } else {
            Chat::sendTextMessageWithInlineKeyboard($this->text_message, $this->inline_keyboard);
        }
    }

    private function makePagination()
    {
        $this->inline_keyboard = (new PaginationkeyBokard(
            $this->inline_keyboard,
            'Next',
            'Previous',
            'time_for_advert_page/' . $this->time_page_number + 1,
            'time_for_advert_page/' . $this->time_page_number - 1,
            $this->time_next_page,
            $this->time_previous_page,
        ))->makeInlinekeyboard();
    }

    private  function assignFields()
    {
        $this->list_of_time = json_decode(PackageRepositoryService::retriveuniqueTime($this->per_page, $this->page_number, $this->number_of_days,$this->level_id)->toJson());
        $this->time_previous_page = $this->list_of_time->prev_page_url;
        $this->time_next_page     = $this->list_of_time->next_page_url;
        $this->time_page_number        = $this->page_number;
    }


    private  function makeTimeTextMessage()
    {
        foreach ($this->list_of_time as $time) {
            $this->text_message .= $this->time_page_number . ", from " . $time->initial_posting_time_in_one_day . '  ' . $time->final_posting_time_in_one_day . "\n";
            $this->package_id = $time->id;
        }
        $this->text_message .= "\n" . " ---------- Page " . $this->time_page_number . ' -----------' . "\n";
    }

    private  function makeTimeSelectionKeyboard()
    {
        self::$inline_keyboard = Keyboard::make()->inline()->row(Keyboard::inlineButton([
            'text'          => 'select this time',
            'callback_data' => 'select_advert/' . $this->package_id
        ]));
    }
}
