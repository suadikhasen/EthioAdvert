<?php

namespace App\TelgramBot\Classes\Advertiser\AddPromotionClass;

use App\TelgramBot\Classes\Advertiser\Common\HowManyDays;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Object\Chat;
use App\TelgramBot\Classes\Advertiser\AddPromotionClass\Package;

class  NumberOfDays extends HowManyDays
{
  
  public  function sendHowmanyDaysMessage($response): void
  {  
     $this->sendMessage();
     Chat::createAnswer($response->id);
     Chat::createQuestion('Add_Promotion', 'how_many_days_is_live');
  }

  public function acceptNumberOfDay($response)
  {
    $checkQCallBackQuery = GeneralService::checkCallBackQuery();
    if($checkQCallBackQuery){
      $queryData = GeneralService::getCallBackQueryData();
       if(GeneralService::checkStartString($queryData,'select_number_of_days')){
          $number_of_days = GeneralService::findAfterString($queryData,'/');
          Chat::$text_message = $number_of_days;
          (new Package())->handle($response,$number_of_days,1,1);
       }else{
         GeneralService::answerCallBackQuery('unknown button');
       }
    }else{
       Chat::sendTextMessage('please use buttons');
    }
  }
}
