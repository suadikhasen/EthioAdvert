<?php

namespace App\TelgramBot\Database\Admin;

use App\BankAccount;

class BankAccountRepository
{
   public static function paymentMethods()
   {
       return BankAccount::all();
   }

   public static function createPaymentMethod($request)
   {
       BankAccount::create([
           'bank_name'  => $request->payment_method_name,
       ]);
   }
}