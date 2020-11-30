<?php

namespace App\TelgramBot\Common\Services\Advertiser;

use App\EthioAdvertPost;
use App\Package;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Database\PackageRepositoryService;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Keyboard\Keyboard;
use App\Temporary;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class PromotionService
{
  private static $advert;
  private static $package;

  public static function checkInlineKeyboardIsTimeSelectionForPromotion()
  {
    if (GeneralService::checkCallBackQuery()) {
      if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(), 'select_advert')) {
        return true;
      }
    }

    return false;
  }

  public static function getCacheNumberOfDays()
  {
    $advert  =  AdvertsPostRepository::findAdvert(Cache::get('edit_advert'.Chat::$chat_id));
    return $advert->package->number_of_days;
  }

  public static function getCacheLevelId()
  {
    $advert  =  AdvertsPostRepository::findAdvert(Cache::get('edit_advert'.Chat::$chat_id));
    return $advert->package->channel_level_id;
  }

  public static function checkTotalPriceOfTheAdvert($packlage_id, $no_channel)
  {
    $package_price_per_channel  = PackageRepositoryService::getPriceOfThePackge($packlage_id);
    return ($package_price_per_channel * $no_channel);
  }

  public static function checkInlinekeyboardIsLevelOfChannel()
  {
    if (GeneralService::checkCallBackQuery()) {
      if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(), 'select_level/')) {
        return true;
      }
    }

    return false;
  }

  public static function getTemporaryNumberOfDays()
  {
    return Temporary::where('chat_id', Chat::$chat_id)->where('type', 'Add_Promotion')->where('question', 'how_many_days_is_live')->latest()->first();
  }

  public static function isLevelOfChannelKeyboard()
  {
    if (GeneralService::checkCallBackQuery() && GeneralService::checkStartString(GeneralService::getCallBackQueryData(), 'level_keyboard_page')) {
      return true;
    }

    return false;
  }


  public static function isTimePageKeyboard()
  {
    if (GeneralService::checkCallBackQuery() && GeneralService::checkStartString(GeneralService::getCallBackQueryData(), 'time_for_advert_page/')) {
      return true;
    }

    return false;
  }

  public static function makeAdvet($advert,$package)
  {
    self::$advert = $advert;
    self::$package = $package;
    Cache::remember('text_detail_advert_info'.$advert->id,Carbon::now()->addMonths(2),function (){
      return self::makeAdvetInformation(self::$advert,self::$package);
   });
  }

  private static function makeAdvetInformation($advert,$package)
  {
    
    
    return '----- <b>Information Of Current Advert:</b> ------' . "\n" . "\n" .
    '       --------  Content Info '.'--------'."\n" . "\n" .
        '<strong> Name Of The Advert:</strong>' . "\n" .
        $advert->name_of_the_advert . "\n" . "\n" .
        '<strong>Description Of The Advert:</strong>' . "\n" . "\n" .
        $advert->description_of_advert . "\n" . "\n" .
        '<strong>Main Message :</strong>' . "\n" . "\n" .
        $advert->text_message . "\n" . "\n" .
        '<strong>Photo Of The Advert :</strong>' . "\n" . "\n" .
        self::photoInfo() . "\n" . "\n" .
        '<strong>Initial Date In Ethiopian Calandar :</strong>' . "\n" . "\n" .
        $advert->initial_date . "\n" ."\n".
        '       -------- Package Information '.'--------'."\n" . "\n" .
        '<strong>Level Of Channel :</strong>' . "\n" . "\n" .
        $package->level->level_name ."\n" . "\n".
        '<strong> Number Of Days :</strong>' . "\n" . "\n" .
        $package->number_of_days. "\n" . "\n" .
        '<strong>Posting Time Ethiopian Calendar:</strong>' . "\n" . "\n" .
        'from '.$package->initial_posting_time_in_one_day.' to '.$package->final_posting_time_in_one_day.
        '<strong>Total Price :</strong>' . "\n" . "\n" .
        $advert->amount_of_payment."\n"."\n".
        '       -------- Status Information '.'--------'."\n" . "\n" .
        '<strong>Approve Status :</strong>'.self::approveStatus(). "\n" . "\n" .
        '<strong>Payment Status :</strong>'.self::paymentStatus() . "\n" . "\n" .
        '<strong>Active Status :</strong> '.self::activeStatus() . "\n" . "\n" ;
  }

  private static function photoInfo()
  {
     if(self::$advert->image_path === null)
     {
       return 'Advert Has No Image';
     }else{
       return 'Photo Attached On The Above';
     }
  }

  private static function approveStatus()
  {
      if(self::$advert->approve_status)
      {
        return 'Approved';

      }

      return 'not approved';
     
  }

  private static function paymentStatus()
  {
    if(self::$advert->payment_status)
    {
      return 'Paid';

    }

    return 'not Paid';
  }

  private static function activeStatus()
  {
    if(self::$advert->active_status === 1)
    {
      return 'Not Posted';

    }elseif(self::$advert->active_status === 2){
       return 'Active Now';
    }elseif(self::$advert->active_status === 3){
       return 'Posted';
    }

    return 'Expired';
  }
}
