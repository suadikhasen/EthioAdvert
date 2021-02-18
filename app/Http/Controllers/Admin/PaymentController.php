<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Common\Payment;
use App\TelgramBot\Database\Admin\PaymentRepository;
use App\TelgramBot\Database\Admin\UserRepository;
use App\Services\Common\TelegramBot;

class PaymentController extends Controller
{
    public static function pendingPayments()
    {
        $pending_payments = PaymentRepository::listOfPendingPayments();
        $total = PaymentRepository::$total;
        return view('admin.channel_owner.pending_payments',['pending_payments'   =>  $pending_payments,'total' => $total]);
    }

    public function goToPayPage($user_id,$pending_payment)
    {
      $user = UserRepository::findUserWithPaymentMethod($user_id);
      return view('admin.channel_owner.pay_channel_owner_page',compact(['user','pending_payment']));
    }

    public function pay($user_id,$paid_amount)
    {
      $payment = PaymentRepository::PayToUser($user_id,$paid_amount);
      $this->sendPaymentNotification($payment);
       return redirect(route('admin.channel_owners.pending_payments'))->with('payment_success','payment paid successfully');
    }

    public function sendPaymentNotification($payment)
    {
       $bot = TelegramBot::initialize();
       $text_message = Payment::makePaymentNotification($payment);
       $bot->sendMessage([$payment->user_id,$text_message,'HTML',true,false]);
    }
}
