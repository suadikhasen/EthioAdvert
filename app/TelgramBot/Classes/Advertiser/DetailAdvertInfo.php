<?php


namespace App\TelgramBot\Classes\Advertiser;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Services\Advertiser\PromotionService;
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
       $this->advert_text_message = PromotionService::makeAdvet($this->advert,$this->advert->package);
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
