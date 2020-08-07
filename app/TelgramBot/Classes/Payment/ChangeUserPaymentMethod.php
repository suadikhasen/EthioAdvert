<?php


namespace App\TelgramBot\Classes\Payment;


use App\TelgramBot\Common\Pages;
use App\TelgramBot\Object\Chat;

class ChangeUserPaymentMethod extends UserPaymentMethod
{
    /**used to handle the request that is coming from change payment method keyboard
     * @param bool $isCommand
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle($isCommand=false)
  {
    if ($isCommand){
        Pages::addNewPaymentMethodPage('update');
    }else{
      $this->processQuestion(Chat::lastAskedQuestion());
    }
  }


}
