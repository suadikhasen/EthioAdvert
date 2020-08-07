<?php


namespace App\TelgramBot\Common\Services\Advertiser;


use App\TelgramBot\Common\GeneralService;
use Illuminate\Support\Str;

/**
 * Class ViewAdvertService
 * @package App\TelgramBot\Common\Services\Advertiser
 */
class ViewAdvertService
{
    /**check if the command is start with 'view_more' and determine
     * if it is the advert
     * @param $command
     * @return bool
     */
    public static function checkCommandIsViewAdvertCommand($command)
  {
      if (Str::startsWith($command,'/view_more'))
          return true;
      return false;
  }

    /**get advert id from the coming command for view adverts
     * example /view_more_5
     * 5 is advertiser id
     * @param $command
     * @return int
     */
    public static function getAdvertIdFromView($command) : int
  {
      return (int) Str::after($command,'/view_more_');
  }

    /**return id of advertiser from view keyboard option
     * @return int
     */
    public static function getIDFromViewKeyboard()
  {
      return (int) Str::after(GeneralService::getCallBackQueryData(),'/');
  }

    /**check the view keyboard option is  'View Full Advert Information Keyboard'
     * @return bool
     */
    public static function checkTheKeyboardIsViewFullAdvertInformation()
  {
      if (GeneralService::isQuery()){
          if (Str::startsWith(GeneralService::getCallBackQueryData(),'view_advert_info'))
              return true;
          return false;
      }
      return false;
  }

  public static function checkIfTheKeyIsBackToViewAdvert()
  {
      if (GeneralService::isQuery()){
          if (Str::startsWith(GeneralService::getCallBackQueryData(),'back_to_view_advert'))
              return true;
          return false;
      }
      return false;
  }
}
