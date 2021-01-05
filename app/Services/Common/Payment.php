<?php

namespace App\Services\Common;

class Payment 
{
    public static function makePaymentNotification($payment)
    {
        return '<i><b>-----Ethio Telegram Advertising Payment---------</b></i>'."\n"."\n".
               '<b>reciever name : </b>'.$payment->payment_holder_name."\n".
               '<b>paid amount : </b>'.$payment->paid_amount."\n".
               '<b>narrative  : </b>'.'Ethio Telegram Advertisment'."\n".
               '<b>payment method  : </b>'.$payment->payment_method_name."\n".
               '<b>payment method  Account Number : </b>'.$payment->identification_number."\n".
               '<b>transaction Id  : </b>'.$payment->id."\n"."\n"."\n".
               'contact us '."\n".
               'phone number'."\n".
               'Ethio Telegram Advertisement ';

    }
}