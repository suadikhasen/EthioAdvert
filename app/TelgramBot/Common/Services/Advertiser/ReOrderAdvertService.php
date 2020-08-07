<?php


namespace App\TelgramBot\Common\Services\Advertiser;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use App\Temporary;
use Illuminate\Support\Facades\Cache;

/**
 * Class ReOrderAdvertService
 * @package App\TelgramBot\Common\Services\Advertiser
 */
class ReOrderAdvertService
{
    /**check if the advert can be re ordered
     * @param $advert_id
     * @return bool
     */
    public static function canBeReOrdered($advert_id)
   {
       if (AdvertsPostRepository::findAdvert($advert_id)->payment_status || AdvertsPostRepository::checkExistenceOfNonPaidPromotion(Chat::$chat_id)){
           return false;
       }
       return false;
   }

    /**
     * @param $advert_id
     */
    public static function putReOrderAdvertIdCache($advert_id)
   {
       Cache::put('re_order_advert_id'.Chat::$chat_id,now()->addMonth(),$advert_id);
   }

    public static function checkCallBackQueryIsReOrdered()
    {
        if (GeneralService::isQuery()){
            if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(),'re_order_advert')){
                return true;
            }
            return false;
        }
        return false;
    }

    public static function getInitialDateForReOrder()
    {
        return Temporary::where('chat_id',Chat::$chat_id)->where('type','re_order_advert')->orderBy('id','desc')->first()->answer;
    }
}
