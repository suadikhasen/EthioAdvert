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

   public static function findPaymentMethod($payment_id)
   {
     return BankAccount::find($payment_id);
   }

   public static function deletePaymentMethod($payment_id)
   {
       self::findPaymentMethod($payment_id)->delete();
   }
}