<?php

namespace App\TelgramBot\Classes\Advertiser\EditAdvert\Packages;

use App\TelgramBot\Classes\Advertiser\ViewAdverts;
use Telegram\Bot\Keyboard\Keyboard;
use App\TelgramBot\Object\Chat;
use App\TelgramBot\Common\GeneralService;
use Illuminate\Support\Facades\Cache;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;

class  EditPackage extends ViewAdverts {

     /**
     *send Edit Options Of The Advert
     */
    public function sendEditOptions()
    {
       $this->makeTextMessage();
       $this->makeEditKeyboard();
       Chat::sendEditTextMessage($this->advert_text_message,$this->advert_keyboards,Chat::$chat_id,GeneralService::getMessageIDFromCallBack());
       GeneralService::answerCallBackQuery('Edit Package Page Displayed');
       if(!EditAdvertService::validateForEditing($this->advert)){
        exit;
     }
    }

    /**
     *make edit keyboard options
     */
    private function makeEditKeyboard()
    {
       $this->advert_keyboards = Cache::remember('edit_package_advert_keyboard'.$this->advert->id,now()->addMonths(2),function (){
           return $this->editKeyboard();
       });
    }

    /**edit keyboards
     * @return Keyboard
     */
    protected function editKeyboard()
    {
        return  Keyboard::make()->inline()->row(Keyboard::inlineButton([
            'text'           => 'Edit Number Of Days',
            'callback_data'  => 'edit_number_of_days/'.$this->advert->id
        ]))->row(Keyboard::inlineButton([
            'text'           => 'Edit Level Of Channel',
            'callback_data'  => 'edit_level_Of_channel/'.$this->advert->id
        ]))->row(Keyboard::inlineButton([
            'text'           => 'Edit Posting Time',
            'callback_data'  => 'edit_posting_time/'.$this->advert->id
        ]))->row(Keyboard::inlineButton([
            'text'           => 'Edit Number Of Channel',
            'callback_data'  => 'edit_number_of_channel/'.$this->advert->id
        ]))->row(Keyboard::inlineButton([
            'text'           => 'Back',
            'callback_data'  => 'edit_advert/'.$this->advert->id
        ]));
    }

}