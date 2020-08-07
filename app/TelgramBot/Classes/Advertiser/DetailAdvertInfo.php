<?php


namespace App\TelgramBot\Classes\Advertiser;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Object\Chat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Keyboard\Keyboard;

class DetailAdvertInfo extends ViewAdverts
{
   public function sendDetailInfo()
   {
       $this->makeInformation();
       $this->sendInformation();
   }

   private function makeInformation()
   {
       $this->makeTextMessage();
       $this->makeKeyboard();
   }

   protected function makeTextMessage()
   {
       $this->advert_text_message = Cache::remember('text_detail_advert_info'.$this->advert->id,Carbon::now()->addMonths(2),function (){
          return $this->textMessage();
       });
   }

   protected function textMessage()
   {

       return
           '<b>Information Of Current Advert:</b>'."\n"."\n".
           '<b>Advert ID :</b>'.
           $this->advert->id."\n\n".
           '<strong>Name :</strong>'."\n".
           $this->advert->name_of_the_advert."\n"."\n".
           '<strong>Description :</strong>'."\n"."\n".
           $this->advert->description_of_advert."\n"."\n".
           '<strong>Main Message :</strong>'."\n"."\n".
           $this->advert->text_message."\n"."\n".
           '<strong>Initial Date :</strong>'."\n"."\n".
           $this->advert->initial_date."\n".
           '<strong>Final Date :</strong>'."\n"."\n".
           $this->advert->final_date."\n\n".
           '<strong>Number Of View :</strong>'."\n"."\n".
           $this->advert->no_view."\n"."\n".
           '<strong>Total Price:</strong>'."\n"."\n".
           GeneralService::checkTotalPriceOfAdvert($this->advert->no_view);
       '<strong>Payment Status:</strong>'.$this->paymentStatus()."\n".
       '<strong>Advert Status:</strong>'.$this->advertStatus()."\n";
   }

    private function paymentStatus()
    {
      if ($this->advert->payment_status)
          return 'Paid';
      return 'Not Paid';
    }

    private function advertStatus()
    {
      if (Carbon::now()->diffInDays($this->advert->initial_date,false) >=1)
          return 'Pending';
      elseif($this->advert->active_status === 1)
          return 'Active';
      elseif($this->advert->active_status === 2)
          return 'Posted';
      return 'Expired';
    }

    private function makeKeyboard()
    {
        $this->advert_keyboards = Cache::remember('keyboard_detail_advert_info'.$this->advert->id,Carbon::now()->addMonths(2),function (){
           return $this->detailInfoKeyBoard();
        });
    }

    private function detailInfoKeyBoard()
    {
        return Keyboard::make()->inline()->row(Keyboard::inlineButton([
            'text'           => 'Back',
            'callback_data'  =>  'back_to_view_advert/'.$this->advert->id
        ]));
    }

    private function checkAdvertHasPhoto()
    {
        if (is_null($this->advert->image_path))
            return false;
        return true;
    }

    private function sendInformation()
    {
        if (!$this->checkAdvertHasPhoto())
            Chat::sendEditTextMessage($this->advert_text_message,$this->advert_keyboards,GeneralService::getChatIdFromCallBack(),GeneralService::getMessageIDFromCallBack());
        else {
            Chat::sendPhoto(Chat::$chat_id,$this->advert->image_path,$this->advert_text_message,$this->advert_keyboards);
        }
        GeneralService::answerCallBackQuery('detail Information displayed');
    }
}
