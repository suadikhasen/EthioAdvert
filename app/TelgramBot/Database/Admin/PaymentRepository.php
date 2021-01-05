<?php

namespace App\TelgramBot\Database\Admin;

use App\BankAccount;
use App\EthioAdvertPost;
use App\PaymentVerification;
use App\Paid;
use App\TelegramPost;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class  PaymentRepository
{
    public static function findAdvertisersPaymentHistory($advertiser_id)
    {
        return PaymentVerification::with(['adverts','paymentMethod','user'])->where('advertiser_id',$advertiser_id)->simplePaginate(5);
    }

    public static function calculateTotalPaymentOfAdvertiser($advertiser_id)
    {
        return EthioAdvertPost::where('advertiser_id',$advertiser_id)->where('payment_status',true)->sum('amount_of_payment');
    }

    public static function paymentHistoryOfChannelOwners($user_id)
    {
        return Paid::where('user_id',$user_id)->simplePaginate(5);
    }

    public static function totalEarningOfChannelOwners($user_id)
    {
        return TelegramPost::where('channel_owner_id',$user_id)->distinct('ethio_advert_post_id')->sum('earning');
    }
    
    public static function monthlyEarning($user_id)
    {
        return TelegramPost::where('channel_owner_id',$user_id)->whereYear('created_at',now()->year)->whereMonth('created_at',now()->month)->distinct('ethio_advert_post_id')->sum('earning');
    }

    public static function pendingPaymentOfChannelOwners($user_id)
    {
        return (self::totalEarninWithOutThisMonth($user_id)- self::totalPaidAmount($user_id) - self::monthlyEarning($user_id));
    }

    public static function totalPaidAmount($user_id)
    {
        return Paid::where('user_id',$user_id)->sum('paid_amount');
    }

    public static function listOfPendingPayments()
    {
       $users = TelegramPost::with('user')->select('channel_owner_id')->whereDate('created_at','<',Carbon::now()->firstOfMonth())->groupBy('channel_owner_id')->get();
       $payments = array();
       foreach($users as $user)
       {
         if($user->pending_payment > 0){
             $payments[$user->channel_owner_id] = $user;
         }
       }
       $payments = new Collection(array_filter($payments));
       return self::paginate($payments,5);
    }

    public static function pendingPaymentOfuser($user_id)
    {
        $total_paid = self::totalPaidAmount($user_id);
        $total_earning_with_out_this_month = self::totalEarninWithOutThisMonth($user_id);
        return ($total_earning_with_out_this_month-$total_paid);
    }

    public static function totalEarninWithOutThisMonth($user_id)
    {
        $earnings = DB::table('telegram_posts')->select(DB::raw('sum(earning) as total_earn,count(*) as total_number,telegram_posts.channel_owner_id,telegram_posts.ethio_advert_post_id'))->where('channel_owner_id',$user_id)->whereDate('created_at','<',Carbon::now()->firstOfMonth())->groupBy(['telegram_posts.ethio_advert_post_id'])->get();
        $total_earning = 0;
        foreach($earnings as $earn){
            $total_earning = $earn->total_earn;
        }
        return $total_earning;
    }

    public static function paginate($items, $perPage = 30) 
    {
        //Get current page form url e.g. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Slice the collection to get the items to display in current page
        $currentPageItems = $items->slice(($currentPage - 1) * $perPage, $perPage);

        //Create our paginator and pass it to the view
        $paginate = new LengthAwarePaginator($currentPageItems, count($items), $perPage);
        $paginate = $paginate->setPath(Request::url());
        return $paginate;
    }

    public static function payToUser($user_id,$paid_amount)
    {
       $user = UserRepository::findUserWithPaymentMethod($user_id);
      return Paid::create([
           'user_id'               => $user_id,
           'paid_amount'           => $paid_amount,
           'payment_method_name'   => $user->payment_method->bank->bank_name,
           'identification_number' => $user->payment_method->account_number,
           'payment_holder_name'   => $user->payment_method->full_name,
       ]);
    }
}