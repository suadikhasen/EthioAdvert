<?php


namespace App\TelgramBot\Common;


use App\TelgramBot\Object\Chat;

class Registered
{
  public function __construct()
  {
      if (!isRegistered(Chat::$chat_id)){
          Pages::startPage();
          return;
      }
  }
}
