<?php


namespace App\TelgramBot\Extend;


use App\TelgramBot\Object\Chat;
use App\UserPaymentMethod;

class UserInformation
{
  public static function paymentInformation(): void
  {
      return UserPaymentMethod::find(Chat::$chat_id);
  }
}
