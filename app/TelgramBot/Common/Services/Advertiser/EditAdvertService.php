<?php


namespace App\TelgramBot\Common\Services\Advertiser;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class EditAdvertService
{
  public static function checkEditAdvertPage()
  {
      if (Chat::$update->isType('callback_query')){
          if(Str::startsWith(GeneralService::getCallBackQueryData(),'edit_advert/'))
              return true;
          return false;
      }
      return false;
  }

  public static  function checkCallBackQueryIsDeleteAdvert()
  {
    if (GeneralService::isQuery())
      {
         if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(),'delete_advert'))
             return true;
         return false;
      }
      return false; 
  }

  public static function checkCallBackQueryIsEditPackage()
  {

    if (GeneralService::isQuery())
      {
         if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(),'edit_package'))
             return true;
         return false;
      }
      return false;
  }

  public static function checkCallBackQueryIsEditName()
  {
      if (GeneralService::isQuery())
      {
         if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(),'edit_advert_name'))
             return true;
         return false;
      }
      return false;
  }

    public static function checkCallBackQueryIsEditDescription()
    {
        if (GeneralService::isQuery())
        {
            if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(),'edit_advert_description'))
                return true;
            return false;
        }
        return false;
    }

    public static function checkCallBackQueryIsEditMainMessage()
    {
        if (GeneralService::isQuery())
        {
            if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(),'edit_main_message'))
                return true;
            return false;
        }
    }

    public static function checkCallBackQueryIsEditDate()
    {
        if (GeneralService::isQuery())
        {
            if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(),'edit_advert_date'))
                return true;
            return false;
        }
    }

    public static function checkCallBackQueryIsEditPhoto()
    {
        if (GeneralService::isQuery())
        {
            if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(),'edit_advert_photo'))
                return true;
            return false;
        }
    }

    public static function checkCallBackQueryIsEditNumberOfView()
    {
        if (GeneralService::isQuery())
        {
            if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(),'edit_advert_view'))
                return true;
            return false;
        }
    }

    public static function removeCacheAdvert($advert_id)
    {
        Cache::forget('advert'.$advert_id);
        Cache::forget('main_advert_keyboard'.$advert_id);
        Cache::forget('advert_text'.$advert_id);
        Cache::forget('text_detail_advert_info'.$advert_id);
        Cache::forget('keyboard_detail_advert_info'.$advert_id);

    }

    public static function putCacheForAdvertId(int $advert_id)
    {
        Cache::put('edit_advert_id'.Chat::$chat_id,$advert_id,now()->addMonth());
    }

    public static function removeCacheEditAdvertId()
    {
        Cache::forget('edit_advert_id'.Chat::$chat_id);
    }

    public static function checkInlineKeyboard($type)
    {
        if (GeneralService::isQuery())
        {
            if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(),$type))
                return true;
        }
        return false;
    }
    
    public static function validateForEditing($advert)
    {
        if($advert->approve_status || $advert->payment_status){
            GeneralService::answerCallBackQuery('you can not edit this advert you can reorder if you want to advert this again');
           return false;
        }else{
            return true;
        }
    }




}
