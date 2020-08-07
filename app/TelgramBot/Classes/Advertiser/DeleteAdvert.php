<?php


namespace App\TelgramBot\Classes\Advertiser;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Keyboard\Keyboard;

class DeleteAdvert extends ViewAdverts
{
  public function beforeDeleteAdvert()
  {
      if ($this->advert->payment_status){
          return 'advert can not be deleted and you can add adverts using add promotion buttons';
      }else{
         $this->makeTextMessage();
         $this->makeKeyboard();
         Chat::sendEditTextMessage($this->advert_text_message,$this->advert_keyboards,Chat::$chat_id,GeneralService::getMessageIDFromCallBack());
      }
  }

  public function makeTextMessage()
  {
     $this->advert_text_message = $this->textMessage();
  }

  public function textMessage()
  {
      return 'Are You Sure you want to remove this advert';
  }

  public function makeKeyboard()
  {
      $this->advert_keyboards = $this->deleteAdvertKeyboard();
  }

    private function deleteAdvertKeyboard()
    {
        return Keyboard::make()->inline()->row(Keyboard::make([
            'text'            => 'Confirm',
            'callback_data'   => 'accept_delete_advert'.$this->advert->id
        ]),
         Keyboard::make([
                'text'            => 'Cancel',
                'callback_data'   => 'cancel_delete_advert'.$this->advert->id
            ]));
    }
}
