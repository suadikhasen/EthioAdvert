<?php

namespace App\TelgramBot\Database\Admin;

use App\listOfPaymentMethod;

class ListOfPaymentMethodRepository
{
    public static function paymentMethodForAdvertiser()
    {
        return listOfPaymentMethod::all();
    }

    public static function createPaymentMethodForAdvertisr($request)
    {
        listOfPaymentMethod::create([
               'bank_name'                    =>      $request->payment_method_name,
               'account_holder_name'          =>      $request->payment_method_holder_name,
               'account_number'               =>      $request->payment_method_holder_identification_number,
        ]);
    }
}