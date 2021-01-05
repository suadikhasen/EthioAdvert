<?php

namespace App\TelgramBot\Database\Admin;

use App\User;

class UserRepository
{
   public static function listOfAdvertiser()
   {
       return User::where('type','Advertiser')->simplePaginate(10);
   }

   public static function findAdvertiser($chat_id)
   {
       return User::find($chat_id);
   }

   public static function findUser($chat_id)
   {
       return User::find($chat_id);
   }

   public static function findUserWithPaymentMethod($user_id)
   {
       return User::with('payment_method.bank')->find($user_id);
   }

   

   
}