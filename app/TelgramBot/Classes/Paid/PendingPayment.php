<?php

namespace App\TelgramBot\Classes\Paid;


use App\TelgramBot\Database\PaidRepository;
use App\TelgramBot\Object\Chat;

class PendingPayment
{


    public function handle(bool $isCommand=false)
    {
        if ($isCommand)
        {
          $total_pending_payment = PaidRepository::totalPendingPaymentOfUser(Chat::$chat_id);
          $total_pending_payment = number_format($total_pending_payment,2,'.',',');
          Chat::sendTextMessage('Your Pending Payment is '.$total_pending_payment);
        }
    }
}
