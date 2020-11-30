<?php

namespace App\TelgramBot\Classes\Advertiser\AddPromotionClass;

use App\TelgramBot\Classes\Advertiser\Common\HowManyDays;
use App\TelgramBot\Database\PackageRepositoryService;
use App\TelgramBot\Object\Chat;
use App\TelgramBot\Database\AdvertsPostRepository;

class  NumberOfDays extends HowManyDays
{
  
  public  function sendHowmanyDaysMessage(): void
  {  
    
    $this->sendMessage();
    Chat::createQuestion('Add_Promotion', 'how_many_days_is_live');

  }

  public function acceptNumberOfDay($response)
  {
    if (in_array(Chat::$text_message, PackageRepositoryService::retriveuniqueDays()->toArray())) {
      Chat::createAnswer($response->id);
      (new LevelOfChannel())->sendLevelofChannelMessage(1);
    } else {
      Chat::sendTextMessage('please send correct number listed on the above');
    }
  }
}
