<?php


namespace App\TelgramBot\Classes\Advertiser;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Keyboard\Keyboard;

class ViewAdverts
{

    protected $advert;
    protected $advert_keyboards;
    protected $advert_text_message;

    function __construct($advert_id)
    {
        $this->advert = AdvertsPostRepository::findAdvert($advert_id);
    }

    public function sendListOfOptions($edit=false)
   {
      $this->makeTextMessage();
      $this->makeMainKeyboard();
       if ($edit){
           Chat::sendEditTextMessage($this->advert_text_message,$this->advert_keyboards,GeneralService::getChatIdFromCallBack(),GeneralService::getMessageIDFromCallBack());
       }else{
           Chat::sendTextMessageWithInlineKeyboard($this->advert_text_message,$this->advert_keyboards);
       }


   }

    protected function makeTextMessage()
    {
      $this->advert_text_message = Cache::remember('advert_text'.$this->advert->id,now()->addMonths(2),function (){
         return $this->textMessage();
      });
    }

    private function textMessage()
    {
       return  '<strong>'.' ----'.$this->advert->name_of_the_advert.' Advert Information</strong> ----'."\n".
        '<strong>price:</strong>'.$this->advert->amount_of_payment."\n".
        '<strong>Initial Date:</strong>'.$this->advert->initial_date."\n";
        
    }

    private function makeMainKeyboard()
    {
        $this->advert_keyboards = Cache::remember('main_advert_keyboard'.$this->advert->id,now()->addMonths(2),function (){
            return $this->mainKeyboard();
        });
    }

   private function mainKeyboard()
   {
       return Keyboard::make()->inline()->row(Keyboard::inlineButton([
           'text'           => 'Edit Advert',
           'callback_data'  => 'edit_advert/'.$this->advert->id
       ]))->row(Keyboard::inlineButton([
           'text'           => 'View Full Advert Information',
           'callback_data'  => 'view_advert_info/'.$this->advert->id
       ]))->row(Keyboard::inlineButton([
           'text'           => 'Re Order Advert',
           'callback_data'  => 're_order_advert/'.$this->advert->id
       ]))->row(Keyboard::inlineButton([
           'text'           => 'Delete Advert',
           'callback_data'  => 'delete_advert/'.$this->advert->id
       ]));
   }


}
