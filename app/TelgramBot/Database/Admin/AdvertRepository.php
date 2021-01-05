<?php

namespace App\TelgramBot\Database\Admin;

use App\EthioAdvertPost;
use App\TelegramPost;
use Carbon\Carbon;

class AdvertRepository
{
    public static function channelsAdvert($channel_id)
    {
      return TelegramPost::with(['adverts'])->where('channel_id',$channel_id)->distinct('ethio_advert_post_id')->paginate(5);
    }

    public static function countNumberOfAdvertInChannel($channel_id)
    {
      return TelegramPost::where('channel_id',$channel_id)->distinct('ethio_advert_post_id')->count();
    }

    public static function totalEarning($channel_id)
    {
       return TelegramPost::with('adverts')->where('channel_id',$channel_id)->distinct('telegram_posts.ethio_advert_post_id')->sum('ethio_advert_posts.one_package_price');
    }

    public static function allAdverts()
    {
        return EthioAdvertPost::simplePaginate(10);
    }

    public static function countAdverts()
    {
      return EthioAdvertPost::count();
    }

    public static function findAdvert($advert_id)
    {
      return EthioAdvertPost::find($advert_id);
    }

    public static function findAdvertOfAdvertiser($advertiser_id)
    {
       return  EthioAdvertPost::where('advertiser_id',$advertiser_id)->simplePaginate(5);
    }

    public static function findPostingAdverts()
    {
      return EthioAdvertPost::where('approve_status',true)->where('payment_status',true)->where('active_status',1)->whereDate('gc_calendar_initial_date','<=',Carbon::today())->where('gc_calendar_final_date','>=',Carbon::today())->simplePaginate(10);
    }

    public static function numberOfPostingAdverts()
    {
      return EthioAdvertPost::where('approve_status',true)->where('payment_status',true)->where('active_status',1)->whereDate('gc_calendar_initial_date','<=',Carbon::today())->where('gc_calendar_final_date','>=',Carbon::today())->count();

    }


    public static function findPostingAdvertsNumberOfAdverts()
    {
      return EthioAdvertPost::where('approve_status',true)->where('payment_status',true)->whereDate('gc_calendar_initial_date','<=',Carbon::now()->date)->where('gc_calendar_final_date','>=',Carbon::now()->date)->count();

    }

    public static function findPostingAdvertsTotalEarn()
    {
      return EthioAdvertPost::where('approve_status',true)->where('payment_status',true)->whereDate('gc_calendar_initial_date','<=',Carbon::now()->date)->where('gc_calendar_final_date','>=',Carbon::now()->date)->sum('earning');

    }

    
}