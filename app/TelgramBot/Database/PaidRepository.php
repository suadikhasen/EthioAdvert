<?php


namespace App\TelgramBot\Database;


use App\Paid;
use App\TelegramPost;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\DB;

/**
 * Class PaidRepository
 * @package App\TelgramBot\Database
 */
class PaidRepository
{
    /**
     * @param $chat_id
     * @return mixed
     */
    public static function userPaidReport($chat_id)
   {
       return Paid::where('user_id',$chat_id)->orderBy('created_at','desc')->simplePaginate(3);
   }

    /**check if there is paid report history for unique user
     * @param $chat_id
     * @return bool
     */
    public static function checkExistenceOfPaidReport($chat_id):bool
   {
       return Paid::where('user_id',$chat_id)->doesntExist();
   }

    /**calculate pending payment of a user
     * @param $chat_id
     * @return
     */
    public static function totalPendingPaymentOfUser($chat_id)
   {
      return (EarningRepository::totalEarningOfUser($chat_id) - EarningRepository::monthlyEarningOfUser($chat_id) - self::totalPaidofUser($chat_id));
   }

   public static function totalPaidofUser($chat_id)
   {
       return Paid::where('user_id',$chat_id)->sum('paid_amount');
   }


}
