<?php

namespace App\TelgramBot\Classes\Advertiser\AddPromotionClass;

use App\TelgramBot\Common\Classes\PaginationkeyBokard;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\BotPackageRepository;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;

class Package
{
    private $number_of_days;
    private $available_package;
    private $text_message = '<b> ➡️ ----- select package -----</b>'."\n\n";
    private $page_number;
    private $per_page;
    private $inline_keyboard;
    private $package_id;
    private $inline = false;
    private $query_data;
    private $response;

    public function handle($response,$number_of_days, $per_page, $page_number)
    {   
        $this->response       = $response;
        $this->query_data     = GeneralService::getCallBackQueryData();
        $this->number_of_days = $number_of_days;
        $this->per_page       = $per_page;
        $this->page_number    = $page_number;
        $this->handlePackage();
    }

    private function handlePackage()
    {
        $this->available_package = BotPackageRepository::findPackagesByNumberOfDays($this->number_of_days, 1, 1);
        $this->text_message      = $this->makeTextMessage();
        $this->inline_keyboard   = $this->makeInlineKeyboard();
        $this->inline_keyboard   = $this->inline_keyboard->row(Keyboard::inlineButton([
            'text'          => 'select',
            'callback_data' => 'select_package/' . $this->package_id
        ]));
        $this->sendMessage();
    }

    private function makeTextMessage()
    {
        $order = GeneralService::orderNumber($this->page_number, $this->per_page);
        foreach ($this->available_package as $package) {
            $this->package_id = $package->id;
            $this->text_message .= '<b>#' . $order . ' </b>' . "\n" .
                '<b>✅ Package Name :     </b>' . $package->package_name . "\n" .
                '<b>✅ Package Id :     </b>' . $package->id . "\n" .
                '<b>✅ Price Per Channel :     </b>' . $package->price . "\n" .
                '<b>✅ Number Of Days :     </b>' . $package->number_of_days . "\n" .
                '<b>✅ Channel Level:     </b>' . $package->level->level_name . "\n" .
                '<b>✅ Initial Posting Time:    </b>' . $package->initial_posting_time_in_one_day . "\n" .
                '<b>✅ Final Posting Time:    </b>' . $package->final_postig_time_in_one_day . "\n\n";
        }
        return $this->text_message;
    }
    /**
     * @return Keyboard
     */
    private function makeInlineKeyboard()
    {
      return (new PaginationkeyBokard(
            null,
             'next',
             'previous',
             'list_of_packages/'.$this->number_of_days.'_'.($this->page_number +1),
             'list_of_packages/'.$this->number_of_days.'_'.($this->page_number-1),
             $this->available_package->nextPageUrl(),
             $this->available_package->previousPageUrl()
        ))->makeInlineKeyboard();
    }

    private function sendMessage()
    {   
        $message_id = GeneralService::getMessageIDFromCallBack();
        if (Str::startsWith($this->query_data, 'list_of_packages')) {
            Chat::sendEditTextMessage(
                $this->text_message,
                $this->inline_keyboard,
                Chat::$chat_id,
                $message_id
            );
        } else {
            Chat::createAnswer($this->response->id);
            Chat::createQuestion('Add_Promotion', 'select_package');
            Chat::sendEditTextMessage(
                $this->text_message,
                $this->inline_keyboard,
                Chat::$chat_id,
                $message_id
            );
        }
        GeneralService::answerCallBackQuery('packge displayed');
    }
}
