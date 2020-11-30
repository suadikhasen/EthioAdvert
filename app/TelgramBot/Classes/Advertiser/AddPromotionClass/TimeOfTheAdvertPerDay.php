<?php

namespace App\TelgramBot\Classes\Advertiser\AddPromotionClass;

use App\TelgramBot\Classes\Advertiser\Common\PostingTime;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Object\Chat;
use App\TelgramBot\Common\Services\Advertiser\PromotionService;
use App\TelgramBot\Common\Services\Advertiser\ViewAdvertService;
use Telgrambot\classes\Advertiser\AddPromotionClass\HowManyChannel;

class  TimeOfTheAdvertPerDay  extends PostingTime
{

  public  function sendTimeMessage($per_page = 1, $page_number = 1, $number_of_days,$level_id, $inline = false)
  {
    $this->sendMessage($per_page, $page_number, $number_of_days,$level_id, $inline);
    if (!$inline) {
      Chat::createQuestion('Add_Promotion', 'time_duration_per_day');
    }
  }

  public function acceptTimeOfTheAdvertPerDay($response)
  {
    if (PromotionService::checkInlineKeyboardIsTimeSelectionForPromotion()) {
      Chat::$text_message = ViewAdvertService::getIDFromViewKeyboard();
      Chat::createAnswer($response->id);
      GeneralService::answerCallBackQuery('time selected successfully');
      (new HowManyChannel())->sendMessage();
    } else {
      Chat::sendTextMessage('please use the givenkeyboard');
    }
  }
}
