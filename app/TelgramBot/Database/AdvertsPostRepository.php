<?php


namespace App\TelgramBot\Database;


use App\EthioAdvertPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Class AdvertsPostRepository
 * @package App\TelgramBot\Database
 */
class AdvertsPostRepository
{
    /**calculate available posts
     * @param $initial_date
     * @param $final_date
     * @return mixed
     */
    public static function numberOfPosts(Carbon $initial_date, Carbon $final_date)
   {

       return EthioAdvertPost::where('approve_status',true)
           ->where('payment_status',true)
           ->whereDate('initial_date','>=',$initial_date)
           ->whereDate('final_date','<',$final_date->addDay())
           ->count();
   }

    /**
     * @param int $advertiser_id
     * @param int $page_number
     * @param int $per_page
     * @return
     */
    public static function promotionsOfUser(int $advertiser_id,int $page_number,int $per_page)
   {
      return EthioAdvertPost::where('advertiser_id',$advertiser_id)->paginate($per_page,['*'],'page',$page_number)->toJson();
   }

    /**
     * @param $advertiser_id
     * @return mixed
     */
    public static function checkExistenceOfNonExpiredUserAdvert($advertiser_id)
   {
       return EthioAdvertPost::where('advertiser_id',$advertiser_id)->where('payment_status',false)->whereDate('final_date','>',Carbon::today())->exists();
   }

   public static function pendingPromotion($advertiser_id)
   {
       return EthioAdvertPost::where('advertiser_id',$advertiser_id)->where('payment_status',false)->first();
   }

   public static function checkExistenceOfNonPaidPromotion($advertiser_id)
   {
       return EthioAdvertPost::where('advertiser_id',$advertiser_id)->where('payment_status',false)->exists();
   }

   public static function findAdvert($advert_id)
   {
       return Cache::remember('advert'.$advert_id,now()->addDays(2),function () use ($advert_id) {
           return EthioAdvertPost::findOrFail($advert_id);
       });
   }
}
