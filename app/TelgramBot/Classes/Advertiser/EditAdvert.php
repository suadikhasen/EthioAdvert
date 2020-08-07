<?php


namespace App\TelgramBot\Classes\Advertiser;



use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class EditAdvert
 * @package App\TelgramBot\Classes\Advertiser
 */
class EditAdvert extends ViewAdverts
{

    /**
     *send Edit Options Of The Advert
     */
    public function sendEditOptions()
    {
       $this->makeTextMessage();
       $this->makeEditKeyboard();
       Chat::sendEditTextMessage($this->advert_text_message,$this->advert_keyboards,Chat::$chat_id,GeneralService::getMessageIDFromCallBack());
       GeneralService::answerCallBackQuery('Edit Page Displayed');
    }

    /**
     *make edit keyboard options
     */
    private function makeEditKeyboard()
    {
       $this->advert_keyboards = Cache::remember('edit_advert_keyboard'.$this->advert->id,now()->addMonths(2),function (){
           return $this->editKeyboard();
       });
    }

    /**edit keyboards
     * @return Keyboard
     */
    protected function editKeyboard()
    {
        return  Keyboard::make()->inline()->row(Keyboard::inlineButton([
            'text'           => 'Edit Name',
            'callback_data'  => 'edit_advert_name/'.$this->advert->id
        ]))->row(Keyboard::inlineButton([
            'text'           => 'Edit Description',
            'callback_data'  => 'edit_advert_description/'.$this->advert->id
        ]))->row(Keyboard::inlineButton([
            'text'           => 'Edit Main Message',
            'callback_data'  => 'edit_main_message/'.$this->advert->id
        ]))->row(Keyboard::inlineButton([
            'text'           => 'Edit Photo',
            'callback_data'  => 'edit_advert_photo/'.$this->advert->id
        ]))->row(Keyboard::inlineButton([
            'text'           => 'Edit Date',
            'callback_data'  => 'edit_advert_date/'.$this->advert->id
        ]))->row(Keyboard::inlineButton([
            'text'           => 'Edit Number Of View',
            'callback_data'  => 'edit_advert_view/'.$this->advert->id
        ]))->row(Keyboard::inlineButton([
            'text'           => 'Back',
            'callback_data'  => 'back_to_view_advert/'.$this->advert->id
        ]));
    }
}
