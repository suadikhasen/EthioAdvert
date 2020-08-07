<?php


namespace App\TelgramBot\Database;


use App\TelegramPost;
use App\TelgramBot\Object\Chat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class EarningRepository
 * @package App\TelgramBot\Database
 */
class EarningRepository
{
    /**total earning of user by telegram channel
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     *
     */
    public static function totalEarningOfUserByChannel()
   {
       return TelegramPost::with('channelsName')->where('channel_owner_id',Chat::$chat_id)->select(DB::raw('sum(earning) as total_channel_earning,channel_id'))->groupBy('channel_id')->get();
   }

    /**today earning of user by telegram channel
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function todayEarningOfUserByChannel()
   {
       return TelegramPost::with('channelsName')->where('channel_owner_id',Chat::$chat_id)->whereDay('created_at',Carbon::today())->select(DB::raw('sum(earning) as total_channel_earning,channel_id'))->groupBy('channel_id')->get();

   }

    /**monthly earning of user by telegram channel
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function monthlyEarningOfUserByChannel()
   {
       return TelegramPost::with('channelsName')->where('channel_owner_id',Chat::$chat_id)->whereMonth('created_at',Carbon::today()->month)->whereYear('created_at',Carbon::now()->year)->select(DB::raw('sum(earning) as total_channel_earning,channel_id'))->groupBy('channel_id')->get();
   }

    /**total earning of user
     * @return mixed
     */
    public static function totalEarningOfUser()
   {
       return TelegramPost::where('channel_owner_id',Chat::$chat_id)->sum('earning');
   }

    /**monthly earning of user
     * @return mixed
     */
    public static function monthlyEarningOfUser()
   {
       return TelegramPost::where('channel_owner_id',Chat::$chat_id)->whereMonth('created_at',Carbon::today()->month)->whereYear('created_at',Carbon::now()->year)->sum('earning');
   }


}
