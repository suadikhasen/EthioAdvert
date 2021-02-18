<?php


namespace App\TelgramBot\Database;


use App\EthioAdvertPost;
use App\TelgramBot\Object\Chat;
use Carbon\Carbon;
use danog\MadelineProto\stats;
use Exception;
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
      $now        = Carbon::now();  
      $before_two_hour = $now->copy()->subHours(2);
       return EthioAdvertPost::where('advertiser_id',$advertiser_id)->where('payment_status',false)->whereBetween('created_at',[$before_two_hour,$now])->exists();
   }

   public static function pendingPromotion($advertiser_id)
   {
       return EthioAdvertPost::where('advertiser_id',$advertiser_id)->where('payment_status',false)->where('approve_status',true)->first();
   }

   public static function checkExistenceOfNonPaidPromotion($advertiser_id)
   {
       return EthioAdvertPost::where('advertiser_id',$advertiser_id)->where('payment_status',false)->exists();
   }

   public static function findAdvert($advert_id)
   {
       return Cache::remember('advert'.$advert_id,now()->addDays(2),function () use ($advert_id) {
           try{
            return EthioAdvertPost::findOrFail($advert_id);
           }catch(Exception $e){
             Chat::sendTextMessage('server error');
             return null;
           }
       });
   }

   public static function havePendingInformation($advertiser_id)
   {
    return EthioAdvertPost::where('advertiser_id',$advertiser_id)->where('payment_status',false)->where('approve_status',true)->exists();
     
   }

   public static function deleteAdvert($advert_id)
   {
      self::findAdvert($advert_id)->delete();
   }

   public static function checkExistenceOfAdvert($advertiser_id)
   {
     return EthioAdvertPost::where('advertiser_id',$advertiser_id)->exists();   
   }

   public static function findAdvertsForPosting()
   {
       return EthioAdvertPost::where('approve_status',true)
       ->where('payment_status',true)
       ->whereDate('gc_calendar_initial_date','<=',now()->today())
       ->whereDate('gc_calendar_final_date','>=',now()->today())
       ->get();
   }

   public static function findActiveAdverts()
   {
       return EthioAdvertPost::where('active_status',3)->paginate(10);
   }

   public static function pendingAdverts()
   {
       return EthioAdvertPost::where('active_status',1)->orderBy('gc_calendar_initial_date','ASC')->paginate(10);
   }
}
