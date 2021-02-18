<?php


namespace App\TelgramBot\Database;

use App\Channels;
use App\EthioAdvertPost;
use App\Services\Common\TelegramBot;
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


  public static function singleChannelEarning($channel_id)
  {

    return TelegramPost::where('channel_id',$channel_id)
    ->where('is_first',true)
    ->sum('earning');

  }

  public static function singleChannelTodayEarning($channel_id)
  {
    return TelegramPost::where('channel_id',$channel_id)
    ->whereDate('created_at',now()->today)
    ->where('is_first',true)
    ->sum('earning');

  }

  public static function singleChannelMonthlyEarning($channel_id)
  {
    return TelegramPost::where('channel_id',$channel_id)
    ->whereYear('created_at',now()->year())
    ->whereMonth('created_at',now()->month)
    ->where('is_first',true)->sum('earning');
  }

  public static function getSumOfEarningOfChannel($unique_advert_id)
  {
    return EthioAdvertPost::whereIn('id', $unique_advert_id)
      ->sum('channel_price');
  }

  public static function getTodayUniqueAdvertForChannel($channel_id)
  {
    return  TelegramPost::distinct()->select(['ethio_advert_post_id'])
      ->where('channel_id', $channel_id)
      ->whereDate('created_at', Carbon::today())
      ->pluck('ethio_advert_post_id')->toArray();
  }

  public static function getTotalUniqueAdvertForChannel($channel_id)
  {
    return  TelegramPost::distinct()->select('ethio_advert_post_id')
      ->where('channel_id', $channel_id)
      ->pluck('ethio_advert_post_id')->toArray();
  }


  public static function uniqueAdvertIdForChannelOwner($chat_id)
  {
    return  DB::table('telegram_posts')
       ->where('channel_owner_id', $chat_id)
       ->distinct()->get(['ethio_advert_post_id','channel_id'])->pluck('ethio_advert_post_id')->toArray();
  }


  public static function getSumOfMonthlyEarningOfUniqueAdverts($unique_advert_id)
  {
    return EthioAdvertPost::whereIn('id', $unique_advert_id)
    ->whereMonth('gc_calendar_initial_date',Carbon::now()->month)
    ->sum('channel_price');
  }

  /**total earning of user
   * @return mixed
   */
  public static function totalEarningOfUser($chat_id)
  {
     return TelegramPost::where('channel_owner_id',$chat_id)
     ->where('is_first',true)
     ->sum('earning');
  }

  /**monthly earning of user
   * @return mixed
   */
  public static function monthlyEarningOfUser($chat_id)
  {
    return TelegramPost::where('channel_owner_id',$chat_id)
    ->whereYear('created_at',now()->year())
    ->whereMonth('created_at',now()->month)
    ->where('is_first',true)->sum('earning');
  }
}
