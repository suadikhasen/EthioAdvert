<?php


namespace App\TelgramBot\Common\Services\Advertiser;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\AdvertsPostRepository;

use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Keyboard\Keyboard;

class NumberOfViewService
{
  public static function makeKeyboardForNumberOfView()
  {
      return Keyboard::make()->inline()->row(Keyboard::inlineButton([
          'text' => '+1k',
          'callback_data' => '+1k'
      ]), Keyboard::inlineButton([
          'text' => '+1Ok',
          'callback_data' => '+10k'
      ]), Keyboard::inlineButton([
          'text' => '+100k',
          'callback_data' => '+100k'
      ]))->row(Keyboard::inlineButton([
          'text' => '-1k',
          'callback_data' => '-1k'
      ]), Keyboard::inlineButton([
          'text' => '-10k',
          'callback_data' => '-10k'
      ]), Keyboard::inlineButton([
          'text' => '-100k',
          'callback_data' => '-100k'
      ]))->row(Keyboard::inlineButton([
          'text' => 'Confirm',
          'callback_data' => 'Confirm',
      ]));
  }

  public static function makeNumberOfViewTextMessage($number_of_view, $total_price, $maximum_view, $per_view)
  {
      return '<i>send number of view by using buttons and use confirm button to send number of view</i>'."\n"."\n".
          '<strong>Total Number Of View :</strong> '.$number_of_view."\n".
          '<strong>Total Price:</strong>'.$total_price."\n".
          '<strong>Maximum View:</strong>'.$maximum_view."\n".
          '<strong>Price Per View:</strong> '.$per_view."\n"."\n".
          '<strong>Click Buttons to Increase and Decrease view</strong>';
  }

  public static function sendViewMessage(int $number_of_view,float $total_price,int $maximum_view,$per_view, $edit=false)
  {
      $text_message = NumberOfViewService::makeNumberOfViewTextMessage($number_of_view,$total_price,$maximum_view,$per_view);
      $keyboard = NumberOfViewService::makeKeyboardForNumberOfView();
      if ($edit)
      {
          Chat::sendEditTextMessage($text_message,$keyboard,GeneralService::getChatIdFromCallBack(),GeneralService::getMessageIDFromCallBack());
          GeneralService::answerCallBackQuery('View Changed Successfully');
      }else {
          Chat::sendTextMessageWithInlineKeyboard($text_message, $keyboard);
      }
  }

  public static function getNumberOfViewFromInLineButtons($data)
  {
      switch ($data)
      {
          case '+1k':
              return 1000;
              break;
          case '+10k':
              return 10000;
              break;
          case '+100k':
              return 100000;
              break;
          case '-1k':
              return -1000;
              break;
          case '-10k':
              return -10000;
              break;
          case '-100k':
              return -100000;
              break;
          case 'Confirm':
              return 'Confirm';
              break;

      }

  }

  public static function sendEditedViewMessage($no_view_keyboard,$response,$edit=false)
  {
      $updated_view = self::calculateUpdateView($no_view_keyboard,$response,$edit);
      if ($updated_view < 0){
          GeneralService::answerCallBackQuery('View Can not be Negative Try Again');
      }elseif ($updated_view < GeneralService::calculateMaximumView()){
          GeneralService::answerCallBackQuery('Number Of View Must Be Less Than Maximum Estimated View');
      }else{
          Chat::$text_message = $updated_view;
          Chat::createAnswer($response->id);
          NumberOfViewService::sendViewMessage($updated_view,GeneralService::checkTotalPriceOfAdvert($updated_view),GeneralService::calculateMaximumView(),GeneralService::per_view_price,true);
      }
  }

    public static function calculateUpdateView($no_view_keyboard,$response,$edit=false): int
    {
        $old_view = $response->answer;
        if ($old_view !== null){
            return (int) $old_view + $no_view_keyboard;
        }elseif ($edit){
            return ((AdvertsPostRepository::findAdvert(Cache::get('edit_advert_id'.Chat::$chat_id))->no_view) + $no_view_keyboard);
        }
        return $no_view_keyboard + GeneralService::default_number_of_view;
    }


    /**
     * @param $response
     * @return mixed
     */
    public static function getNumberOfViewForEditing($response)
    {
        if ($response->answer === null){
            return AdvertsPostRepository::findAdvert(Cache::get('edit_advert_id'.Chat::$chat_id))->no_view;
        }
        return $response->answer;
    }
}
