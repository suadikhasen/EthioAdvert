<?php


namespace App\TelgramBot\Classes\Advertiser;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Common\Services\CacheService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Keyboard\Keyboard;

class DeleteAdvert extends ViewAdverts
{
  public function deleteAdvert()
  {
      if ($this->advert->payment_status){
          GeneralService::answerCallBackQuery('paid advert can not be deleted if you want to order another you can add new',true);
      }else{
         AdvertsPostRepository::deleteAdvert($this->advert->id); 
         EditAdvertService::removeCacheAdvert($this->advert->id);
         Chat::sendEditTextMessage('advert deleted successfully',$this->advert_keyboards,Chat::$chat_id,GeneralService::getMessageIDFromCallBack());
      }
  }

  

    
}
