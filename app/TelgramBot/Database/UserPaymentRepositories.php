<?php


namespace App\TelgramBot\Database;


use App\TelgramBot\Object\Chat;
use App\UserPaymentMethod;

class UserPaymentRepositories
{
  public static  function userPaymentMethod():UserPaymentMethod
  {
      return UserPaymentMethod::find(Chat::$chat_id);
  }

  public static function deletePaymentMethod(): void
  {
    UserPaymentMethod::where('chat_id',Chat::$chat_id)->delete();
  }


}
