<?php


namespace App\TelgramBot\Common\Services;


use Illuminate\Support\Facades\Cache;

class CacheService
{
    public static function putPerDayPostCache($channel_id,$chat_id)
    {
       Cache::put('per_day_post_'.$chat_id,$channel_id,now()->addDays(2));
    }

    public static function removePerDayPostCache($chat_id)
    {
        Cache::forget('per_day_post_'.$chat_id);
    }

    public static function getPerDayPostCache($chat_id)
    {
        return Cache::get('per_day_post_'.$chat_id);
    }
}
