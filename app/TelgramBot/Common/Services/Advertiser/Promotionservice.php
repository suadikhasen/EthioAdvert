<?php

namespace App\TelgramBot\Common\Services\Advertiser;

use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Database\PackageRepositoryService;
use App\TelgramBot\Object\Chat;
use App\Temporary;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class PromotionService
{
  private static $advert;

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

  public static function makeAdvet($advert)
  {
    self::$advert = $advert;
    return Cache::remember('text_detail_advert_info'.$advert->id,Carbon::now()->addMonths(2),function (){
       return self::makeAdvetInformation(self::$advert);
   });
  }

  private static function makeAdvetInformation($advert)
  {
    return '⟹ <b> Advert Information </b> '."\n\n" .
        '<b>------------Advert Content------------ </b>'."\n\n".
        '⇒ <strong> Name :</strong>'."\n\n" .
        $advert->name_of_the_advert . "\n\n" .
        '⇒ <strong> Description :</strong>' ."\n\n".
        $advert->description_of_advert."\n\n".
        '⇒ <strong> Main Message :</strong>'."\n\n".
        $advert->text_message ."\n\n".
        '⇒ <strong> Photo :</strong>'."\n\n".
        self::photoInfo()."\n\n".
        '<b>------------Date and Time------------ </b>'."\n\n".
        '⇒ <strong> Initial Date :</strong>'."\n\n".
        $advert->gc_calendar_initial_date ."\n\n".
        '⇒ <strong> Final Date :</strong>'."\n\n".
        $advert->gc_calendar_final_date ."\n\n".
        '⇒ <strong> Posting Time  :</strong>'."\n\n".
        'from '.$advert->initial_time .' to '.$advert->final_time."\n\n".
        '<b>------------Package And Price------------</b>'."\n\n".
        '⇒ <strong>Package Name :</strong>'."\n\n".
         $advert->package_name."\n\n".
        '⇒ <strong> Number Of Days :</strong>'."\n\n".
        $advert->number_of_days."\n\n" .
        '⇒ <strong> Package Price :</strong>'."\n\n".
        $advert->one_package_price."\n\n".
        '⇒ <strong> Number Of Channel :</strong>'."\n\n".
        $advert->number_of_channel."\n\n".
        '⇒ <strong>Total Price :</strong>'."\n\n". 
        $advert->amount_of_payment."\n\n".
        '<b>------------Status------------</b>'."\n\n".
        '⇒ <strong>Approve Status : </strong>'.self::approveStatus()."\n\n".
        '⇒ <strong>Payment Status : </strong>'.self::paymentStatus()."\n\n".
        '⇒ <strong>Active Status : </strong> '.self::activeStatus()."\n\n";
  }

  private static function photoInfo()
  {
     if(self::$advert->image_path === null)
     {
       return 'Advert Has No Image.';
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
      return 'Pending';

    }elseif(self::$advert->active_status === 2){
       return 'Waiting For Posting';
    }elseif(self::$advert->active_status === 3){
       return 'active';
    }elseif(self::$advert->active_status === 4){
      return 'Finished';
    }
    return 'Expired';
  }
}
