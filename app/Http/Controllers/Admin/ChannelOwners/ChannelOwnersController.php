<?php

namespace App\Http\Controllers\Admin\ChannelOwners;

use App\Http\Controllers\Controller;
use App\Paid;
use App\TelgramBot\Database\Admin\ChannelOwnerRepository;
use App\TelgramBot\Database\Admin\ChannelRepository;
use App\TelgramBot\Database\Admin\PaymentRepository;
use App\TelgramBot\Database\Admin\UserRepository;
use Illuminate\Http\Request;

class ChannelOwnersController extends Controller
{
      public function listOfChannelOwners()
      {
          $channel_owners = ChannelOwnerRepository::listOfChannelOwners();
          return view('admin.channel_owner.list_of_channel_owners',['channel_owners' => $channel_owners]);
      }

      public static function listOfChannelOwnersChannel($channel_owners_id)
      {
        $channels = ChannelRepository::channelsOfUser($channel_owners_id);
        return view('admin.channels.list_of_channels',['channels' => $channels]);
      }

      public static function listOfPayment($user_id)
      {
        $payments           =   PaymentRepository::paymentHistoryOfChannelOwners($user_id);
        $total_earning      =   PaymentRepository::totalEarningOfChannelOwners($user_id);
        $monthly_earning    =   PaymentRepository::monthlyEarning($user_id);
        $channel_owner      =   UserRepository::findUser($user_id);
        $pending_amount     =   PaymentRepository::pendingPaymentOfChannelOwners($user_id);
        $total_paid_amount  =   PaymentRepository::totalPaidAmount($user_id);
        return view('admin.channel_owner.payment_history_of_user',compact('payments','total_earning','monthly_earning','pending_amount','total_paid_amount','channel_owner'));
      }
}
